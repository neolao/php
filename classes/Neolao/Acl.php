<?php
/**
 * @package Neolao
 */
namespace Neolao;

/**
 * ACL (Access Control List)
 */
class Acl
{
    /**
     * Available resources
     *
     * @var array
     */
    protected $_resources;

    /**
     * Roles
     *
     * @var array
     */
    protected $_roles;

    /**
     * Rules
     *
     * @var array
     */
    protected $_rules;

    /**
     * Cache used by the method "isAllowed"
     *
     * @var array
     */
    protected $_cache;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_resources   = [];
        $this->_roles       = [];
        $this->_rules       = [];
        $this->_cache       = [];
    }

    /**
     * Add a resource
     *
     * @param   string      $resourceName   The resource name
     */
    public function addResource($resourceName)
    {
        if (in_array($resourceName, $this->_resources)) {
            return;
        }
        $this->_resources[] = $resourceName;

        // Empty the cache
        $this->_emptyCache();
    }

    /**
     * Add a role
     *
     * @param   string      $roleName       The role name
     * @param   string      $parentName     The parent name
     */
    public function addRole($roleName, $parentName = null)
    {
        if (array_key_exists($roleName, $this->_roles)) {
            return;
        }
        $this->_roles[$roleName] = null;

        if ($parentName && array_key_exists($parentName, $this->_roles)) {
            $this->_roles[$roleName] = $parentName;
        }

        // Empty the cache
        $this->_emptyCache();
    }

    /**
     * Add a rule "allow"
     *
     * @param   string      $roleName       The role name
     * @param   string      $resourceName   The resource name
     * @param   string      $privilegeName  The privilege name
     */
    public function allow($roleName = '*', $resourceName = '*', $privilegeName = '*')
    {
        $this->addResource($resourceName);
        $this->addRole($roleName);

        $this->_rules[] = ['allow', $resourceName, $privilegeName, $roleName];

        // Empty the cache
        $this->_emptyCache();
    }

    /**
     * Add a rule "deny"
     *
     * @param   string      $roleName       The role name
     * @param   string      $resourceName   The resource name
     * @param   string      $privilegeName  The privilege name
     */
    public function deny($roleName = '*', $resourceName = '*', $privilegeName = '*')
    {
        $this->addResource($resourceName);
        $this->addRole($roleName);

        $this->_rules[] = ['deny', $resourceName, $privilegeName, $roleName];

        // Empty the cache
        $this->_emptyCache();
    }

    /**
     * Check if a role is the child of an another role
     *
     * @param   string      $roleName       The role name
     * @param   string      $parentName     The parent name
     * @return  bool                        true if the role is the child of the specified parent
     */
    public function isChild($roleName, $parentName)
    {
        // If the role does not exists, then it is the child of nothing
        if (!array_key_exists($roleName, $this->_roles)) {
            return false;
        }

        // If the first parent is the specified parent, then it is ok
        $firstParentName = $this->_roles[$roleName];
        if ($firstParentName === $parentName) {
            return true;
        }

        // Check with the first parent now
        return $this->isChild($firstParentName, $parentName);
    }

    /**
     * Check if a role is allowed to access to a resource
     * 
     * @param   string      $roleName       The role name
     * @param   string      $resourceName   The resource name
     * @param   string      $privilegeName  The privilege name
     * @return  bool                        true if the role is allowed, false otherwise
     */
    public function isAllowed($roleName, $resourceName, $privilegeName = '*')
    {
        // Check the cache
        $cacheKey = $roleName . '_' . $resourceName . '_' . $privilegeName;
        if (array_key_exists($cacheKey, $this->_cache)) {
            return $this->_cache[$cacheKey];
        }

        // Default value
        $allowed = true;

        // Check each rule of the resource
        // The order is important
        foreach ($this->_rules as $rule) {
            list($ruleType, $ruleResource, $rulePrivilege, $ruleRole) = $rule;

            // Skip the rule if the resources don't match
            if ($ruleResource !== '*' && $ruleResource !== $resourceName) {
                continue;
            }

            // Skip the rule if the roles don't match
            if ($ruleRole !== '*' && $ruleRole !== $roleName && !$this->isChild($roleName, $ruleRole)) {
                continue;
            }

            // Skip the rule if the privileges don't match
            if ($rulePrivilege !== '*' && $rulePrivilege !== $privilegeName) {
                continue;
            }

            // Update the status
            if ($ruleType === 'deny') {
                $allowed = false;
            } else {
                $allowed = true;
            }
        }

        // Fill the cache
        $this->_cache[$cacheKey] = $allowed;

        // Return the value
        return $allowed;
    }

    /**
     * Empty the cache
     */
    protected function _emptyCache()
    {
        $this->_cache = [];
    }
}

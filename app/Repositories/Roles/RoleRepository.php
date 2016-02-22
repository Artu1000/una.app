<?php

namespace App\Repositories\Roles;

use App\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{

    public function __construct()
    {
        $this->model = new Role();
    }

    /**
     * @param array $inputs
     * @return array
     */
    public function translatePermissionsSlugs(array $inputs)
    {
        // for each permission contained in the config file
        foreach (array_dot(config('permissions')) as $permission => $value) {
            // we translate the permission slug to match the key format found in the request params
            $translated_slug = str_replace('.', config('permissions.separator'), $permission);
            // if the permission is detected in the request params
            if (isset($inputs[$translated_slug])) {
                // we add the correct permission slug and its value to the request params
                $inputs[$permission] = $inputs[$translated_slug];
                // if the permission is not a parent permission (detection of a dot)
                if (strpos($permission, '.')) {
                    // we delete the translated slug in the request params
                    unset ($inputs[$translated_slug]);
                }
            }
        }

        return $inputs;
    }
}
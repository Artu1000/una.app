<?php

namespace App\Helpers;

use Modal;
use Sentinel;

class PermissionHelper
{
    /**
     * @param array $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (!Sentinel::getUser()->hasAccess($permission)) {
            Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" .
                trans('permissions.' . $permission) . "</b>",
            ], 'error');

            return false;
        }

        return true;
    }

    /**
     * @param array $permission
     * @return bool
     */
    public function hasPermissionJson($permission)
    {
        if (!Sentinel::getUser()->hasAccess($permission)) {
            return trans('permissions.message.access.denied', ['permission' => trans('permissions.' . $permission)]);
        }

        return false;
    }
}
<?php

namespace App\Helpers;

class CustomLogHelper
{
    /**
     * Make log error more readable
     * @param $error
     */
    public function error($error)
    {
        \Log::info('===================== ERROR =====================');
        \Log::error($error . PHP_EOL);
    }

    /**
     * Make http requests logs more readable
     */
    public function httpRequests()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            $method = !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
            $uri = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

            \Log::info('===================== HTTP =====================');
            \Log::info(implode(' - ', [$method, $uri]) . PHP_EOL);
        }
    }

    /**
     * Make sql requests logs more readable
     */
    public function sqlRequests()
    {
        \DB::listen(
            function ($sql) {
                // $sql is an object with the properties:
                //  sql: The query
                //  bindings: the sql query variables
                //  time: The execution time for the query
                //  connectionName: The name of the connection

                // To save the executed queries to file:
                // Process the sql and the bindings:
                foreach ($sql->bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $sql->bindings[$i] = "'$binding'";
                        }
                    }
                }

                // Insert bindings into query
                $query = str_replace(['%', '?'], ['%%', '%s'], $sql->sql);
                $query = vsprintf($query, $sql->bindings);

                \Log::info('===================== SQL =====================');
                \Log::info(date('Y-m-d H:i:s') . ': ' . $query . PHP_EOL);
            }
        );
    }
}
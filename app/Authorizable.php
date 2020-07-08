<?php

namespace Mnemosine;
/*
 * A trait to handle authorization based on users permissions for given controller
 */

trait Authorizable
{
    /**
     * Abilities
     *
     * @var array
     */
    //movimientos.update
    private $abilities = [
        'index' => 'ver',
        'edit' => 'editar',
        'listRecords' => 'ver',
        'show' => 'ver',
        'update' => 'editar',
        'create' => 'agregar',
        'store' => 'agregar',
        'destroy' => 'eliminar',
        'objetos_add' => 'agregar',
        'resumen_authorizar' => 'ver',
    ];

    /**
    * Se utiliza para vincular subsecciones con la ruta a la que se le concede el permiso
    */
    private $routes = [
        'consultas' => 'consultas',
        'inventario' => 'inventario',
        'investigacion' => 'investigacion',
        'restauracion' => 'restauracion',
        'usuarios' => 'usuarios',
        'roles' => 'roles',
        'reportes' => 'reportes',
        'catalogos' => 'catalogos',
        'catalogoElementos' => 'catalogos',
        'conjuntos' => 'catalogos',
        'generos' => 'catalogos',
        'configuraciones' => 'configuraciones',
        'campos' => 'configuraciones',
        'movimientos' => 'movimientos',
        'instituciones' => 'movimientos',
        'contactos' => 'movimientos',
        'exposiciones' => 'movimientos',
        'sedes' => 'movimientos',
        'resumenMov' => 'movimientos',
    ];

    /**
     * Override of callAction to perform the authorization before it calls the action
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function callAction($method, $parameters)
    {
        if( $ability = $this->getAbility($method) ) {
            $this->authorize($ability);
        }

        return parent::callAction($method, $parameters);
    }

    /**
     * Get ability
     *
     * @param $method
     * @return null|string
     */
    public function getAbility($method)
    {
        $routeName = explode('.', \Request::route()->getName());
        $action = array_get($this->getAbilities(), $method);
        $route = array_get($this->getRoutes(), $routeName[0]);
        //dd($action ? $action . '_' . $route : null);

        return $action ? $action . '_' . $route : null;
    }

    /**
     * @return array
     */
    private function getAbilities()
    {
        return $this->abilities;
    }

    /**
     * @return array
     */
    private function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param array $abilities
     */
    public function setAbilities($abilities)
    {
        $this->abilities = $abilities;
    }
}

<?php
interface IApiUse
{
    public function Add($request, $response, $args);
    public function Get($request, $response, $args);
    public function GetAll($request, $response, $args);
    public function Delete($request, $response, $args);
    public function Update($request, $response, $args);
}

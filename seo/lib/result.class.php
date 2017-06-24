<?php
/*
 * @Author: Jeay 
 * @Date: 2017-06-24 12:44:49 
 * @Last Modified by: Jeay
 * @Last Modified time: 2017-06-24 13:59:04
 */
class Result 
{
    private $config;
    private $type;
    function __construct(Array $config = [], Array $type = [])
    {
        $this->config = $config;
        $this->type = $type;
    }
    public function GetClassify()
    {
        switch ($this->type) {
            case 'index':
                $this->GetIndex();
                break;
            
            default:
                # code...
                break;
        }
    }
}

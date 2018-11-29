<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/4
 * Time: 15:17
 */

namespace App\Admin\Model;

use SsdPHP\Core\Model;

class Admin_Menu extends Model {



    public function getMenuTreeByUrl($routeUrl="index/index"){

    }

    /**
     * 通过pid获取菜单
     * @param int $pid
     * @return mixed
     */
    public function getMenuTreeByPid($pid=0,$feild=array("*")){
        $p_menu = $this->select(array("pid"=>$pid),$feild)->items;
        return $p_menu;
    }

    /**
     * 获取所有
     * @param int $pid
     * @return mixed
     */
    public function getAll($cond = array(),$feild=array("id","title","pid","url","icon_class","descript","status")){
        $p_menu = $this->select($cond,$feild)->items;
        return $p_menu;
    }


    public function getTreeAll($status_cond=array('is_del'=>0)){

        $feild=array(
            'id','title','pid','url','icon_class','status'
        );
        $order="sort asc";

        $p_menu = $this->select(array("pid"=>0)+$status_cond,$feild,"",$order)->items;
        if(empty($p_menu)){
            return array();
        }

        foreach ($p_menu as $key=>&$v){
            $sub_menu = $this->select(array("pid"=>$v['id'])+$status_cond,$feild,"",$order)->items;

            if(!empty($sub_menu)){
                $v['child']=$sub_menu;
                foreach ($v['child'] as &$_v){

                    $_sub_menu = $this->select(array("pid"=>$_v['id'])+$status_cond,$feild,"",$order)->items;
                    if(!empty($_sub_menu)){
                        $_v['child']=$_sub_menu;
                    }
                }

            }
        }
        //print_r($p_menu);
        return $p_menu;
    }

    /**
     * 通过id获取菜单
     * @param int $id
     * @return mixed
     */
    public function getMenuById($id,$feild=array("*")){
        if(empty($id)){
            return array();
        }
        $p_menu = $this->selectOne(array("id"=>$id),$feild);
        return !empty($p_menu)?$p_menu:array();
    }

    /**
     * 通过url获取菜单
     * @param int $id
     * @return mixed
     */
    public function getMenuByUrl($url,$feild=array("*")){
        if(empty($url)){
            return array();
        }
        $p_menu = $this->selectOne(array("url"=>$url),$feild);
        return !empty($p_menu)?$p_menu:array();
    }

    /**
     * 处理后台菜单模版显示数据
     */
    public function handleAdminMenu($currententry){
        if(empty($currententry)){
            return array();
        }
        $feild=array(
            'id','title','pid','url','icon_class'
        );
        $order="sort asc";
        $current = $this->selectOne(array("url"=>$currententry),$feild);
        $node = $this->getPath($current['id']);
        $p_menu = $this->select(array("pid"=>0,'status'=>1,'is_del'=>0),$feild,"",$order)->items;
        if(empty($p_menu)){
            return array();
        }
        $rules = Admin_Auth_Group_Access::$rules;
        $is_root = Admin_Auth_Group_Access::ISROOT();
        foreach ($p_menu as $key=>&$v){
            if(!in_array($v['id'],$rules) && $is_root==false){
               unset($p_menu[$key]);
               continue;
            }
            $v['hover']="";
            $sub_menu = $this->select(array("pid"=>$v['id'],'status'=>1,'is_del'=>0),$feild,"",$order)->items;
            if($v['id'] === $node[0]['id']){
                $v['hover']="active";
            }
            if(!empty($sub_menu)){
                foreach ($sub_menu as $k2=>&$s_v){
                    if(!in_array($s_v['id'],$rules) && $is_root==false){
                        unset($sub_menu[$k2]);
                        continue;
                    }
                    $s_v['hover']="";
                    if(!empty($node[1]['id']) && $s_v['id'] === $node[1]['id']){
                        $s_v['hover']="active";
                    }
                }
                $v['child']=$sub_menu;
            }
        }
        //print_r($p_menu);
        return array(
            'admin_menu'=>$p_menu,
            'nav_url'=>$node,
        );


    }

    //获取树的根到子节点的路径
    public function getPath($id){
        $path = array();
        $feild=array('id','title','pid','url','icon_class');
        $nav = $this->selectOne(array("id"=>$id),$feild);
        $path[] = $nav;
        if($nav['pid'] >1){
            $path = array_merge($this->getPath($nav['pid']),$path);
        }
        return $path;
    }

    /**
     * 添加菜单
     * @param $data
     * @return array
     */
    public function add($data){

        if(empty($data) || empty($data['title'])){
            return -1;
        }
        if(isset($data['id'])){
            unset($data['id']);
        }
        $data['create_time']=time();
        $id = $this->insert($data);

        if(!empty($id)){
            return $id;
        }
        return -2;


    }
    /**
     * 修改菜单
     * @param $data
     * @return mixed
     */
    public function edit($data){


        if(empty($data) || empty($data['id'])){

            return -1;
        }
        if(isset($data['pid'])){
            unset($data['pid']);
        }
        if(isset($data['sort_pid'])){
            $data['pid']=$data['sort_pid'];
            unset($data['sort_pid']);
        }
        $data['update_time']=time();
        $id = $this->update(array("id"=>$data['id']),$data);

        if(!empty($id)){
            return $id;
        }

        return -2;


    }
}
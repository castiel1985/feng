<?php
namespace Manage\Controller;
use Think\Controller;
use Think\Page;
class ResourceController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->videoLogic =  D('Video','Logic');
        $this->appsLogic =  D('Apps','Logic');
        $this->Video=  M('Video');
        $this->Apps=  M('App');
    }

    private $videoLogic ;
    private $Video ;
    private $appsLogic ;
    private $Apps ;

    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }

    // 视频
    public function videomgr(){
        $this->checkPriv('1_1_1');
        $p = C('ADMIN_REC_PER_PAGE');      // 获取后台分页数    
        $count=$this->videoLogic->getVideoTotal();
        $vid=D('video');
        $page=new Page($count, $p);
    	$res=$vid->where('isdel is null')->order(array('creatime'=>desc))->limit($page->firstRow,$page->listRows)->select();
        $this->data = $res;  
        $this->total = $this->videoLogic->getVideoTotal(); 
        $show=$page->show(); 
        $this->assign('page',$show);
        $this->display();
    }
    public function videoplay(){
        $uuid=$_GET['uuid'];
        $name=$_GET['name'];
        $type=$_GET['type'];
        if($type==2){
            $uuid=$uuid.'/'.'1';
        } 
        $this->assign('uuid',$uuid);
        $this->assign('name',$name);
        $this->display("Resource/videoplay");
    }

    public function readvideo(){       
        $uuid=$_GET['uuid'];
        $curl = curl_init();
        $url=C('API_QUERRY').$uuid;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        $data='['.$data.']';
        $data= json_decode($data);
        $this->assign('uuid',$uuid);
        $this->assign('da',$data[0]);
        $this->display("Resource/readvideo"); 
    }

    public function addvideo(){
        $this->checkPriv('1_1_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        $uuid=$this->videoLogic->getUuid();
        $this->assign('uuid',$uuid); 
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['type'] = I('post.type');
            $newdata['duratime'] = I('post.duratime');
            $newdata['director'] = I('post.director');
            $newdata['actors'] = I('post.actors');
            $newdata['setnum'] = I('post.setnum','','int');
            $newdata['country'] = I('post.country');
            $newdata['category'] = I('post.category');
            $newdata['years'] = I('post.years','','int');
            $newdata['intro'] = I('post.intro');
            $newdata['uuid'] = I('post.uuid');
            if($newdata['setnum'] == 0 || $newdata['years'] == 0){$this->error('集数或年代不能为空，请填入合理数字');}
            $upres = $this->upimgfile();
            if($upres['error'] == false){
                $newdata['cover'] = $upres['result']['coverimg']['fullpath'];
            }
            $ret = $this->Video->add($newdata);
            if($ret){
                $this->redirect('Resource/videomgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $this->display("Resource/videoedit");
        }
    }

    public function editvideo(){
        $this->checkPriv('1_1_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['name'] = I('post.name');
            $newdata['type'] = I('post.type');
            $newdata['duratime'] = I('post.duratime');
            $newdata['director'] = I('post.director');
            $newdata['actors'] = I('post.actors');
            $newdata['setnum'] = I('post.setnum','','int');
            $newdata['country'] = I('post.country');
            $newdata['category'] = I('post.category');
            $newdata['years'] = I('post.years','','int');
            $newdata['intro'] = I('post.intro');
            if($newdata['setnum'] == 0 || $newdata['years'] == 0){$this->error('集数或年代不能为空，请填入合理数字');}
            $upres = $this->upimgfile();
            if($upres['error'] == false){
                $newdata['cover'] = $upres['result']['coverimg']['fullpath'];
            }
            $ret = $this->Video->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Resource/videomgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->display('Resource/videomgr');
            }
        }else{
            $id = I('get.id','','int');
            $this->data = $this->videoLogic->getVideoById($id);
            $this->display("Resource/videoedit");
        }
 
    }

    public function delvideo(){
        $this->checkPriv('1_1_4');
        $id = I('get.id','','int');
        echo $id;
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->Video->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }

    public function chgvideostatus(){
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        if($id){
            if($status == 1){
                $this->Video->where('id='.$id)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 2){
                $this->Video->where('id='.$id)->save(array('status'=>2));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else{
                $this->error('无该状态设置');
            }
        }else{
            $this->error('该记录不存在');
        }
    }
    // 应用
    public function appmgr(){
        $this->checkPriv('1_3_1');
        $p = getCurPage();
        $res = $this->appsLogic->getAppList(array(),$p);
        $this->data = $res;
        $this->total = $this->appsLogic->getAppTotal();
        $show = constructAdminPage($this->total);
        $this->assign('page',$show);
		$this->display();
    }

    public function addapp(){
        $this->checkPriv('1_3_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['slogon'] = I('post.slogon');
            $newdata['package'] = I('post.package');
            $newdata['pubdate'] = I('post.pubdate');
            $newdata['version'] = I('post.version');
            $newdata['size'] = I('post.size');
            $newdata['tags'] = I('post.tags');
            $newdata['intro'] = I('post.intro');
            $newdata['pubuser'] = I('post.pubuser');
            $newdata['recommendtxt'] = I('post.recommendtxt');
            $newdata['updatetxt'] = I('post.updatetxt');
            $newdata['apptype'] = 2;
            $upres = $this->upimgfile();
            if($upres['error'] == false){
                $newdata['icon'] = $upres['result']['iconimg']['fullpath'];
            }
            $imgs = I('post.img');
            $newdata['imgs'] = json_encode($imgs);
            $ret = $this->Apps->add($newdata);
            if($ret){
                $this->redirect('Resource/appmgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $this->display("Resource/appedit");
        }
    }

    public function editapp(){
        $this->checkPriv('1_3_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['name'] = I('post.name');
            $newdata['slogon'] = I('post.slogon');
            $newdata['package'] = I('post.package');
            $newdata['pubdate'] = I('post.pubdate');
            $newdata['version'] = I('post.version');
            $newdata['size'] = I('post.size');
            $newdata['tags'] = I('post.tags');
            $newdata['intro'] = I('post.intro');
            $newdata['pubuser'] = I('post.pubuser');
            $newdata['recommendtxt'] = I('post.recommendtxt');
            $newdata['updatetxt'] = I('post.updatetxt');
            $upres = $this->upimgfile();
            if($upres['error'] == false){
                $newdata['icon'] = $upres['result']['iconimg']['fullpath'];
            }
            $imgs = I('post.img');
            $newdata['imgs'] = json_encode($imgs);
            $ret = $this->Apps->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Resource/appmgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->display('Resource/appmgr');
            }
        }else{
            $id = I('get.id','','int');
            $data = $this->appsLogic->getAppsById($id);
            $this->assign('simgs',json_decode($data['imgs']));
            $this->data = $data;
            $this->display("Resource/appedit");
        }
    }

    public function delapp(){
        $this->checkPriv('1_3_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->Apps->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }

    public function chgappstatus(){
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        if($id){
            if($status == 1){
                $this->Apps->where('id='.$id)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 2){
                $this->Apps->where('id='.$id)->save(array('status'=>2));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else{
                $this->error('无该状态设置');
            }
        }else{
            $this->error('该记录不存在');
        }
    }
    // 游戏
    public function gamemgr(){
        $this->checkPriv('1_2_1');
        $p = getCurPage();
        $res = $this->appsLogic->getGameList(array(),$p);
        $this->data = $res;
        $this->total = $this->appsLogic->getGameTotal();
        $show = constructAdminPage($this->total);
        $this->assign('page',$show);
        $this->display();
    }

    public function addgame(){
        $this->checkPriv('1_2_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['slogon'] = I('post.slogon');
            $newdata['pubdate'] = I('post.pubdate');
            $newdata['package'] = I('post.package');
            $newdata['version'] = I('post.version');
            $newdata['size'] = I('post.size');
            $newdata['tags'] = I('post.tags');
            $newdata['intro'] = I('post.intro');
            $newdata['pubuser'] = I('post.pubuser');
            $newdata['recommendtxt'] = I('post.recommendtxt');
            $newdata['updatetxt'] = I('post.updatetxt');
            $newdata['apptype'] = 1;
            $upres = $this->upimgfile();
            if($upres['error'] == false){
                $newdata['icon'] = $upres['result']['iconimg']['fullpath'];
            }
            $imgs = I('post.img');
            $newdata['imgs'] = json_encode($imgs);
            $ret = $this->Apps->add($newdata);
            if($ret){
                $this->redirect('Resource/gamemgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $this->display("Resource/gameedit");
        }
    }

    public function editgame(){
        $this->checkPriv('1_2_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['name'] = I('post.name');
            $newdata['slogon'] = I('post.slogon');
            $newdata['package'] = I('post.package');
            $newdata['pubdate'] = I('post.pubdate');
            $newdata['version'] = I('post.version');
            $newdata['size'] = I('post.size');
            $newdata['tags'] = I('post.tags');
            $newdata['intro'] = I('post.intro');
            $newdata['pubuser'] = I('post.pubuser');
            $newdata['recommendtxt'] = I('post.recommendtxt');
            $newdata['updatetxt'] = I('post.updatetxt');
            $upres = $this->upimgfile();
            if($upres['error'] == false){
                $newdata['icon'] = $upres['result']['iconimg']['fullpath'];
            }
            $imgs = I('post.img');
            $newdata['imgs'] = json_encode($imgs);
            $ret = $this->Apps->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Resource/gamemgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->display('Resource/gamemgr');
            }
        }else{
            $id = I('get.id','','int');
            $data = $this->appsLogic->getAppsById($id);
            $this->assign('simgs',json_decode($data['imgs']));
            $this->data = $data;
            $this->display("Resource/gameedit");
        }
    }

    public function delgame(){
        $this->checkPriv('1_2_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->Apps->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }

    public function chggamestatus(){
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        if($id){
            if($status == 1){
                $this->Apps->where('id='.$id)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 2){
                $this->Apps->where('id='.$id)->save(array('status'=>2));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else{
                $this->error('无该状态设置');
            }
        }else{
            $this->error('该记录不存在');
        }
    }
    public function tmpupimgs(){
        $bimgs = $this->upimgfile();
        if($bimgs['error'] != true){
            $ret = array();
            foreach($bimgs['result'] as $img){
                $ret[] = $img['fullpath'];
            }
            $this->ajaxReturn($ret);
        }else{
            echo 0;
        }
    }

    private function upimgfile(){
        $ret = array();
        $upload =  new \Think\Upload();
        $upload->maxSize       = C('ITEM_IMG_MAXSIZE');;
        $upload->exts          = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
        $upload->rootPath      = C('ITEM_IMG_PATH');
        $upload->subName       = array('date', 'Ym');
        $upfinfo = $upload->upload();
        if(!$upfinfo) {// 上传错误提示错误信息
            $ret['error'] = true;
            $ret['result'] = $upload->getError();
            //$this->error($upload->getError());
        }else{// 上传成功
            foreach($upfinfo as $k=>&$file){
                $file['fullpath'] = $upload->rootPath.$file['savepath'].$file['savename'];
            }
            $ret['error'] = false;
            $ret['result'] = $upfinfo;
        }
        return $ret;
    }
}
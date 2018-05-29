<?php
namespace Notadd\Foundation\Administration\Controllers;

use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Member\Models\MemberGroup;
use Notadd\Member\Models\MemberGroupRelation;
/**
 * Class InformationsController.
 */
class InformationsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $user = $this->jwt->parseToken()->authenticate();
        $group=MemberGroup::query()->where('identification', 'channel')->first();
        $member=MemberGroupRelation::query()->where('member_id',$user->id)->first();
        if($group->id==$member->group_id){
            return $this->response->json([
                'data'    => [
                    'navigation'  =>[
                        'notadd/live/content'=>['children'=>['notadd/live/content/6/0'=>
                            ['children'=>null,'icon'=>'ios-folder','enabled'=>true,'index'=>'notadd/live/content/6/0','order'=>0,'parent'=>'notadd/live/content','path'=>'/live/channel','text'=>'渠道管理']
                        ],'enabled'=>true,'icon'=>'play','index'=>'notadd/live/content','order'=>1,'parent'=>'notadd/live','path'=>'/live','permission'=>null,'text'=>'渠道管理']],
                    'pages'       => $this->administration->pages()->toArray(),
                    'scripts'     => $this->administration->scripts()->toArray(),
                    'stylesheets' => $this->administration->stylesheets()->toArray(),
                ],
                'message' => '获取模块和插件信息成功！',
                'us'=>$user,
                'group'=> $member
            ]);
        }else{
            return $this->response->json([
                'data'    => [
                    'navigation'  => $this->administration->navigations()->toArray(),
                    'pages'       => $this->administration->pages()->toArray(),
                    'scripts'     => $this->administration->scripts()->toArray(),
                    'stylesheets' => $this->administration->stylesheets()->toArray(),
                ],
                'message' => '获取模块和插件信息成功！',
                'us'=>$user,
                'group'=> $member
            ]);
        }


    }
}

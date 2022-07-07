<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages =  Message::query()->paginate(20);
        return MessageResource::collection($messages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|min:2|max:300',
        ]);
        if(Auth::guard('api')->check()){
            $user = Auth::guard('api')->user();
            $message = new Message();
            $message -> text =  $validated['text'];
            $message ->  user_id =  $user->id;
            $message -> date =  Carbon::now();
            $message -> author =  $user -> name;
            $message -> save();
            return new MessageResource($message);
        }
        else{
            return response(['message'=>'Вы не авторизованы']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new MessageResource(Message::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!Auth::guard('api')->check()){
            return response(['message'=>'Вы не авторизованы']);
        }
        $user =Auth::guard('api')->user();
        $message = Message::findOrFail($id);

        if($user->id != $message->user_id){
            return response(['message'=>'Вы не являетесь автором']);
        }
        if((Carbon::now()->diffInDays($message->date))>=1) {
            return response(['message'=>'Прошло больше дня с момента создания']);
        }
        $message->delete();
        return response(['message'=>'Успешно удалена запись']);
    }
}

<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Resources\NotificationCollection;
use App\Models\User;
use Auth;

class NotificationComposer
{


    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $perpage = 10;
        if (Auth::check()) {
            $user_notifications= Auth::User()->notifications()->paginate($perpage);
        }else{
            $user_notifications = array();
        }
        // $user_notifications = new NotificationCollection($notifications);
        $view->with('user_notifications', $user_notifications);
    }
}

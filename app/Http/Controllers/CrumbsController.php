<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrumbsController extends Controller
{
    public function getCrumbs($page)
    {
        switch ($page) {
            case 'booking':
                $crumbs = array(
                    'page'=>'Booking',
                    'pages'=>[
                        ['title'=>'Reservation','url' => '#']
                    ]);
                break;
            case 'ticket':
                $crumbs = array(
                    'page'=>'Ticket',
                    'pages'=>[
                        ['title'=>'Reservation','url' => '#']
                    ]);
                break;
            case 'calendar':
                $crumbs = array('page'=>'Ticket','pages'=>[['title'=>'Reservation','url' => '#']]);
                break;
            case 'requests':
                $crumbs = array('page'=>'Requests','pages'=>[['title'=>'Reservation','url' => '#']]);
                break;
            default:
                $crumbs = array(
                    'page'=>'Booking',
                    'pages'=>[
                        ['title'=>'Reservation','url' => '#']
                    ]);
                break;
        }
        echo json_encode($crumbs);
    }
}

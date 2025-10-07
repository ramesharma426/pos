<?php


namespace App\Traits;

trait Messages
{
    public function getSuccessMessage($name)
    {
        return ['message' => $name . ' created successfully.' ];
    }

    public function getUpdateMessage($name)
    {
        return ['message' => $name . ' updated successfully.' ];
    }

    public function getDestroyMessage($name)
    {
        return ['message' => $name . ' removed successfully.' ];
    }

    public function getMessage($msg)
    {
        return ['message' => $msg ];
    }

    public function getErrorMessage($msg)
    {
        return ['message' => $msg ];
    }

//    public function getTaskSuccessMessage($msg)
//    {
//        return session()->flash('success', $msg);
//    }

}

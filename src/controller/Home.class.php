<?php
class Home extends Controller
{
    public function showAction()
    {
        // Show HTML view: /view/home/show.phtml
        // Default behavior, no need to do anything
    }

    public function dataAction()
    {
        $data = [
            'message' => 'Hello from the backend!',
            'timestamp' => date('Y-m-d H:i:s'),
        ];
        $this->asJson($data);
    }

    public function customViewAction()
    {
        $this->setView('custom/special'); // loads /view/custom/special.phtml
    }
}
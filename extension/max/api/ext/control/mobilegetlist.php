<?php
class api extends control
{
    /**
     * Get full data list.
     * 
     * @param  string $type     full or increment
     * @param  string $object   task / bug / todo / story / all
     * @param  int    $range    get data id less than range, 0 is no limit.
     * @param  string $last     last sync time.
     * @param  int    $records  the max records 
     * @param  string $format   index or all.
     * @access public
     * @return void
     */
    public function mobileGetList($type = 'full', $object = 'all', $range = 0, $last= '', $records = 1000, $format = 'index')
    {
        if($this->get->lang and $this->get->lang != $this->app->getClientLang())
        {
            $this->app->setClientLang($this->get->lang);
            $this->app->loadLang('api');
        }

        if(!$this->loadModel('user')->isLogon()) die(json_encode(array('status' => 'failed', 'error' => 'offline', 'reason' => $this->lang->api->failLogin)));

        $account = $this->app->user->account;

        $data = array("time"=>time());
        if($last) $last = date('Y-m-d H:i:s', $last);
        if($object == 'all' and $range != 0) $range = 0;
        if($object == 'task' or $object == 'all')
        {
            $funcName = 'get' . ucfirst($type) . 'Task';
            $tasks    = $this->api->$funcName($range, $last, $records);
            $tasks    = $this->api->process('task', $tasks, $last, $format);
            if($tasks) $data['task'] = $tasks;
        }
        if($object == 'story' or $object == 'all')
        {
            $funcName = 'get' . ucfirst($type) . 'Story';
            $stories  = $this->api->$funcName($range, $last, $records);
            $stories  = $this->api->process('story', $stories, $last, $format);
            if($stories) $data['story'] = $stories;
        }
        if($object == 'bug' or $object == 'all')
        {
            $funcName = 'get' . ucfirst($type) . 'Bug';
            $bugs     = $this->api->$funcName($range, $last, $records);
            $bugs     = $this->api->process('bug', $bugs, $last, $format);
            if($bugs) $data['bug'] = $bugs;
        }
        if($object == 'todo' or $object == 'all')
        {
            $funcName = 'get' . ucfirst($type) . 'Todo';
            $todos    = $this->api->$funcName($account, $range, $last, $records);
            $todos    = $this->api->process('todo', $todos, $last, $format);
            if($todos) $data['todo'] = $todos;
        }
        if($object == 'product' or $object == 'all')
        {
            $funcName = 'get' . ucfirst($type) . 'Product';
            $products = $this->api->$funcName($account, $range, $last, $records);
            $products = $this->api->process('product', $products, $last, $format);
            if($products) $data['product'] = $products;
        }
        if($object == 'project' or $object == 'all')
        {
            $funcName = 'get' . ucfirst($type) . 'Project';
            $projects = $this->api->$funcName($account, $range, $last, $records);
            $projects = $this->api->process('project', $projects, $last, $format);
            if($projects) $data['project'] = $projects;
        }

        die($this->api->compression($data));
    }
}

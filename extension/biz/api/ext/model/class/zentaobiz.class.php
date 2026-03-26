<?php
class zentaobizApi extends apiModel
{
    public function buildExportAPI($api)
    {
        $api->params   = json_decode($api->params, true);
        $api->response = json_decode($api->response, true);

        $typeList = $this->getTypeList($api->lib);

        $api->content  = "<p>{$this->lang->api->path}： {$api->path} <br />{$this->lang->api->method}： {$api->method}</p>";
        $api->content .= "<div>{$api->desc}</div>";
        if($api->params['header'])
        {
            $api->content .= "<h3>{$this->lang->api->header}</h3>";
            $api->content .= "<table>";
            $api->content .= "<tr>";
            $api->content .= "<td>{$this->lang->api->req->name}</td>";
            $api->content .= "<td>{$this->lang->api->req->type}</td>";
            $api->content .= "<td>{$this->lang->api->req->required}</td>";
            $api->content .= "<td>{$this->lang->api->req->desc}</td>";
            $api->content .= "</tr>";
            foreach($api->params['header'] as $param)
            {
                $api->content .= "<tr>";
                $api->content .= "<td>{$param['field']}</td>";
                $api->content .= "<td>String</td>";
                $api->content .= "<td>{$this->lang->api->boolList[$param['required']]}</td>";
                $api->content .= "<td>{$param['desc']}</td>";
                $api->content .= "</tr>";
            }
            $api->content .= "</table>";
        }
        if($api->params['query'])
        {
            $api->content .= "<h3>{$this->lang->api->query}</h3>";
            $api->content .= "<table>";
            $api->content .= "<tr>";
            $api->content .= "<td>{$this->lang->api->req->name}</td>";
            $api->content .= "<td>{$this->lang->api->req->type}</td>";
            $api->content .= "<td>{$this->lang->api->req->required}</td>";
            $api->content .= "<td>{$this->lang->api->req->desc}</td>";
            $api->content .= "</tr>";
            foreach($api->params['query'] as $param)
            {
                $api->content .= "<tr>";
                $api->content .= "<td>{$param['field']}</td>";
                $api->content .= "<td>String</td>";
                $api->content .= "<td>{$this->lang->api->boolList[$param['required']]}</td>";
                $api->content .= "<td>{$param['desc']}</td>";
                $api->content .= "</tr>";
            }
            $api->content .= "</table>";
        }
        if($api->params['params'])
        {
            $api->content .= "<h3>{$this->lang->api->params}</h3>";
            $api->content .= "<table>";
            $api->content .= "<tr>";
            $api->content .= "<td>{$this->lang->api->req->name}</td>";
            $api->content .= "<td>{$this->lang->api->req->type}</td>";
            $api->content .= "<td>{$this->lang->api->req->required}</td>";
            $api->content .= "<td>{$this->lang->api->req->desc}</td>";
            $api->content .= "</tr>";
            foreach($api->params['params'] as $item) $api->content .= $this->parseResponseTree($item, $typeList);
            $api->content .= "</table>";
        }
        if($api->paramsExample)
        {
            $api->content .= "<h3>{$this->lang->api->paramsExample}</h3>";
            $api->content .= nl2br($api->paramsExample);
        }
        if($api->response)
        {
            $api->content .= "<h3>{$this->lang->api->response}</h3>";
            $api->content .= "<table>";
            $api->content .= "<tr>";
            $api->content .= "<td>{$this->lang->api->req->name}</td>";
            $api->content .= "<td>{$this->lang->api->req->type}</td>";
            $api->content .= "<td>{$this->lang->api->req->required}</td>";
            $api->content .= "<td>{$this->lang->api->req->desc}</td>";
            $api->content .= "</tr>";
            foreach($api->response as $item) $api->content .= $this->parseResponseTree($item, $typeList);
            $api->content .= "</table>";
        }
        if($api->responseExample)
        {
            $api->content .= "<h3>{$this->lang->api->responseExample}</h3>";
            $api->content .= nl2br($api->responseExample);
        }
        return $api;
    }

    public function parseResponseTree($data, $typeList, $level = 0)
    {
        $str   = '<tr>';
        $field = '';
        for($i = 0; $i < $level; $i++) $field .= '&nbsp;&nbsp;'. ($i == $level-1 ? '∟' : '&nbsp;') . '&nbsp;&nbsp;';

        $field .= $data['field'];
        $str   .= '<td>' . $field . '</td>';
        $str   .= '<td>' . zget($typeList, $data['paramsType'], '') . '</td>';
        $str   .= '<td>' . zget($this->lang->api->boolList, $data['required'], '') . '</td>';
        $str   .= '<td>' . $data['desc'] . '</td>';
        $str   .= '</tr>';
        if(isset($data['children']) && count($data['children']) > 0)
        {
            $level++;
            foreach($data['children'] as $item) $str .= $this->parseResponseTree($item, $typeList, $level);
        }
        return $str;
    }
}

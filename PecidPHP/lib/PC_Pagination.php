<?php 
class PC_Pagination extends PC_Lib{

    protected $_config = array(
            'page_size'     =>  10,     //每页数量
            'max_page_show' =>  10,     //最多显示几页
            'first_text'    =>  '<<',   //如果不为空，显示首页
            'end_text'      =>  '>>',   //如果不为空，显示尾页
            'next_text'     =>  '>',    //如果不为空，显示下一页
            'prev_text'     =>  '<',    //如果不为空，显示上一页
    );

    /*
     * 获取html
     *      
    **/
    function show($cur_page, $data_count){
        // 参数校验
        if(!is_int($cur_page) || $cur_page < 1){
            trigger_error('cur_page必须为大于等于的整数', E_USER_ERROR);
        }
        if(!is_int($data_count) || $data_count < 0){
            trigger_error('data_count必须为正整数', E_USER_ERROR);
        }

        if($data_count == 0 || $this->_config['page_size'] == 0 || $this->_config['max_page_show'] == 0){
            return '';
        }

        $total_page_count = intval(ceil($data_count / $this->_config['page_size']));
        if($total_page_count == 1 || $cur_page > $total_page_count){
            return '';
        }

        if($total_page_count <= $this->_config['max_page_show']){
            $start = 1;
            $end = $total_page_count;
        }else{
            $half = $this->_config['max_page_show'] / 2;
            if($this->_config['max_page_show'] % 2 == 0){
                $before_count = $half;
                $after_count = $half - 1;
            }else{
                $before_count = $after_count= $half;
            }
            $start = $cur_page - $before_count;
            $end = $cur_page + $after_count;

            if($start < 1){
                $end = $end + (1 - $start);
                $start = 1;
            }elseif($end > $total_page_count){
                $start = $start - ($end - $total_page_count);
                $end = $total_page_count;
            }
        }

        $str = '';

        if($cur_page == 1){
            $str .= $this->_config['first_text'] ? '<span>'.$this->_config['first_text'].'</span>' : '';
            $str .= $this->_config['prev_text'] ? '<span>'.$this->_config['prev_text'].'</span>' : '';
        }else{
            $str .= $this->_config['first_text'] ? '<a href="#"><span>'.$this->_config['first_text'].'</a></span>' : '';
            $str .= $this->_config['prev_text'] ? '<a href="#"><span>'.$this->_config['prev_text'].'</a></span>' : '';
        }


        for($i = $start; $i <= $end; $i++){
            if($i == $cur_page){
                $str .= '<span>'.$i.'</span>';
            }else{
                $str .= '<a href="#"><span>'.$i.'</span></a>';
            }
        }

        if($cur_page == $total_page_count){
            $str .= $this->_config['next_text'] ? '<span>'.$this->_config['next_text'].'</span>' : '';
            $str .= $this->_config['end_text'] ? '<span>'.$this->_config['end_text'].'</span>' : '';
        }else{
            $str .= $this->_config['next_text'] ? '<a href="#"><span><span>'.$this->_config['next_text'].'</a></span>' : '';
            $str .= $this->_config['end_text'] ? '<a href="#"><span><span>'.$this->_config['end_text'].'</a></span>' : '';
        }

        return $str;
    }

}
?>
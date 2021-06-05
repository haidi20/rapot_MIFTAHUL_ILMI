<?php
use Carbon\Carbon;

if( ! function_exists('active_menu') )
{
    function active_menu($pattern)
    {
        return request()->is($pattern) ? 'active' : '';
    }
}

if( ! function_exists('active_menu_list') )
{
    function active_menu_list($pattern)
    {
        return request()->is($pattern) ? 'pcoded-trigger' : '';
    }
}

if( ! function_exists('val_checkbox') )
{
    function val_checkbox($value) {
        return $value ? 1 : 0;
    }

}

if( ! function_exists('format_money') )
{
    function format_money($data){
        $data = str_replace('.','',str_replace(',00','',$data));

        return number_format($data, 2, ',', '.');
    }
}

// kebutuhan surat tanggal pemeriksaan
if ( ! function_exists('inspection_date') )
{
    function inspection_date($data, $type){
        // if($data == '0000-00-00') return null;

        Carbon::setLocale('id');

        $date       = Carbon::parse($data);
        $name_day   = trans('date.day.'.$date->format('l'));
        $date_day   = $date->format('d');
        $month      = trans('date.month.'.$date->format('n'));
        $year       = $date->format('Y');

        if($type == 'day'){
            return $name_day;
        }else if($type == 'date'){
            return $date_day;
        }else if($type == 'month'){
            return $month;
        }else if($type == 'year'){
            return $year;
        }
    }
}

if( ! function_exists('flash_message') )
{
    function flash_message($session, $type='info', $icon='', $messages='', $close=true)
    {
        $button = ($close) ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' : '';
        $icon = ($icon != '') ? fa($icon).'&nbsp; ' : '';

        session()->flash($session, '<div class="alert alert-dismissible alert-'.$type.'">
                                        '.$icon.' '.$messages.' '.$button.'
                                    </div>');
    }
}

if( ! function_exists('fa') ){
    function fa($icon='pencil', $addClass='', $style='')
    {
        return '<i class="fa fa-'.$icon.' '.$addClass.'" style="'.$style.'"></i>';
    }
}

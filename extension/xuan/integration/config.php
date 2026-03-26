<?php
/* Theme for doc */
$collaboraDocTheme = array();
$collaboraDocTheme['co-primary-element']       = '#1651aa';
$collaboraDocTheme['co-primary-element-light'] = '#c8d4e6';
$collaboraDocTheme['co-txt-accent']            = '#38257a';
$collaboraDocTheme['co-primary-text']          = '#fff';
$collaboraDocTheme['co-border-radius']         = '1px';
$collaboraDocTheme['co-color-main-text']       = '#333435';
$collaboraDocTheme['co-body-bg']               = '#fff';

/* Theme for ppt */
$collaboraPptTheme = $collaboraDocTheme;
$collaboraPptTheme['co-primary-element']       = '#b13719';
$collaboraPptTheme['co-primary-element-light'] = '#e9cec8';

/* Theme for excel */
$collaboraExcelTheme = $collaboraDocTheme;
$collaboraExcelTheme['co-primary-element']       = '#0f703b';
$collaboraExcelTheme['co-primary-element-light'] = '#c7dace';

/* Set all themes to $config->collaboraThemes */
$config->collaboraThemes = array();
$config->collaboraThemes['doc']  = $collaboraDocTheme;
$config->collaboraThemes['docx'] = $collaboraDocTheme;
$config->collaboraThemes['ppt']  = $collaboraPptTheme;
$config->collaboraThemes['pptx'] = $collaboraPptTheme;
$config->collaboraThemes['xls']  = $collaboraExcelTheme;
$config->collaboraThemes['xlsx'] = $collaboraExcelTheme;

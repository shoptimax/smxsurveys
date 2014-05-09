<?php
/**
 * Metadata version
 */
$sMetadataVersion = '1.1';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'smxsurveys',
    'title'        => 'Shoptimax Surveys',
    'description'  => array(
        'de' => 'Shoptimax Umfragemodul',
        'en' => 'Shoptimax surveys module',
    ),
    'thumbnail'    => 'box_smxsurveys.jpg',
    'version'      => '0.5.0',
    'author'       => 'shoptimax GmbH',
    'url'          => 'http://www.shoptimax.de',
    'email'        => 'support@shoptimax.de',
    'extend'       => array(
        'oxlang'                 => 'shoptimax/smxsurveys/core/smxsurveys_oxlang',
        'oxcmp_user'             => 'shoptimax/smxsurveys/application/components/smxcmp_survey',
    ),
    'files' => array(
        'smxsurveys_setup'                 => 'shoptimax/smxsurveys/core/smxsurveys_setup.php',
        'smxsurveys_admin'                 => 'shoptimax/smxsurveys/application/controllers/admin/smxsurveys_admin.php',
        'smxsurveys_answers'               => 'shoptimax/smxsurveys/application/controllers/admin/smxsurveys_answers.php',
        'smxsurveys_list'                  => 'shoptimax/smxsurveys/application/controllers/admin/smxsurveys_list.php',
        'smxsurveys_main'                  => 'shoptimax/smxsurveys/application/controllers/admin/smxsurveys_main.php',
        'smxsurveys_mall'                  => 'shoptimax/smxsurveys/application/controllers/admin/smxsurveys_mall.php',
        'smxsurveys_questions'             => 'shoptimax/smxsurveys/application/controllers/admin/smxsurveys_questions.php',
        'smxsurveys_results'               => 'shoptimax/smxsurveys/application/controllers/admin/smxsurveys_results.php',
        'smxsurveys'                       => 'shoptimax/smxsurveys/application/models/smxsurveys.php',
        'smxsurveysanswer2question'        => 'shoptimax/smxsurveys/application/models/smxsurveysanswer2question.php',
        'smxsurveysanswers'                => 'shoptimax/smxsurveys/application/models/smxsurveysanswers.php',
        'smxsurveyslist'                   => 'shoptimax/smxsurveys/application/models/smxsurveyslist.php',
        'smxsurveysparticipant'            => 'shoptimax/smxsurveys/application/models/smxsurveysparticipant.php',
        'smxsurveysquestion'               => 'shoptimax/smxsurveys/application/models/smxsurveysquestion.php',
    ),
    'templates' => array(
        "message/smxsurvey.tpl"            => "shoptimax/smxsurveys/views/basic/tpl/smxsurvey.tpl",
        "custom/smxsurvey.tpl"            => "shoptimax/smxsurveys/views/basic/tpl/smxsurvey.tpl",
        "smxsurveys_admin.tpl"             => "shoptimax/smxsurveys/views/admin/tpl/smxsurveys_admin.tpl",
        "smxsurveys_answers.tpl"           => "shoptimax/smxsurveys/views/admin/tpl/smxsurveys_answers.tpl",
        "smxsurveys_list.tpl"              => "shoptimax/smxsurveys/views/admin/tpl/smxsurveys_list.tpl",
        "smxsurveys_main.tpl"              => "shoptimax/smxsurveys/views/admin/tpl/smxsurveys_main.tpl",
        "smxsurveys_admin.tpl"             => "shoptimax/smxsurveys/views/admin/tpl/smxsurveys_admin.tpl",
        "smxsurveys_questions.tpl"         => "shoptimax/smxsurveys/views/admin/tpl/smxsurveys_questions.tpl",
        "smxsurveys_results.tpl"           => "shoptimax/smxsurveys/views/admin/tpl/smxsurveys_results.tpl",
    ),
    'blocks' => array(
        array(
            'template' => 'layout/sidebar.tpl',
            'block'    => 'sidebar_categoriestree',
            'file'     => '/application/views/blocks/block_smxsurvey.tpl'
        ),
    ),
    'events' => array(
        'onActivate' => 'smxsurveys_setup::onActivate',
    ),
   'settings' => array(
    )
);
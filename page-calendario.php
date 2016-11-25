<?php
/**
 * Template geral para 'pages'
 */

get_header(); ?>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php boros_breadcrumb(); ?>
            </div>
            <div class="col-md-8">
                <?php
                if (have_posts()){
                    custom_content_nav( 'nav_above' );
                    while (have_posts()){
                        the_post();
                        get_template_part( 'content', 'page' );
                        
                        $config = array(
                            'post_type'             => 'post',
                            //'post_meta'             => 'performance_date',
                            //'day'         => 1,
                            //'month'       => 11,
                            //'year'        => 2011,
                            //'accepted_metas' => array('performance_date'),
                            //'taxonomies' => array('category', 'post_tag'),
                            'taxonomies' => 'category',
                            'extra_row' => true,
                            'delete_cache_var' => 'delete_cache',
                        );
                        // variáveis de url
                        if( isset($_GET['cm']) ){
                            $config['month'] = (int) $_GET['cm'];
                        }
                        if( isset($_GET['ca']) ){
                            $config['year'] = (int) $_GET['ca'];
                        }
                        
                        $calendar = boros_calendar($config);
                        //pre($calendar);
                        //$calendar->get_posts_table_by_post_meta();
                        //$calendar->get_posts_table_by_date();
                        
                        $calendar->get_posts();
                        echo '<div id="calendar-table-box">';
                            echo "<h2 class='calendar-month-name'>{$calendar->__get('month_name')}</h2>";
                            $calendar->show_calendar_head();
                            $calendar->show_calendar_table();
                        echo '</div>';
                        $calendar->show_calendar_footer();
                    }
                }
                ?>
                <style type="text/css">
                .show-events-btn {
                    background-color: green;
                }
                .show-events-btn.opened {
                    background-color: red;
                }
                .event-circle {
                    display: inline-block;
                    border-radius: 50%;
                    border: 1px solid #000;
                    width: 10px;
                    height: 10px;
                }
                table.calendar {
                    width: 100%;
                }
                table.calendar .events-list {
                    list-style: none;
                    margin: 0;
                    padding: 0;
                }
                table.calendar .events-list img {
                    width: 100%;
                }
                
                @media only screen and (max-width: 768px) {
                    table.calendar , 
                    table.calendar thead, 
                    table.calendar tbody, 
                    table.calendar th, 
                    table.calendar td, 
                    table.calendar tr {
                        display:block;
                    }
                    table.calendar .has-events {
                        display:block;
                    }
                    table.calendar td.cell-events {
                        height:auto;
                    }
                    table.calendar {
                        border:0;
                        border-bottom:1px solid #fdc222;
                    }
                    table.calendar th,
                    table.calendar td,
                    table.calendar td.cell-header,
                    table.calendar .event-btn-ovelay {
                        display:none;
                        border:none;
                    }
                    table.calendar tr{
                        border:none;
                    }
                    table.calendar td.cell-events {
                        border:1px solid #fdc222;
                        border-bottom:none;
                        padding:0;
                    }
                    table.calendar td.cell-events .events-list {
                        display: block !important;
                    }
                    table.calendar tr.week-extra td {
                        border:0;
                    }
                    .agenda-no-posts {
                        background-color: #fff4d4;
                        border: 2px solid #fff;
                        padding:10px;
                        text-align:center;
                    }
                    table.empty-calendar {
                        display:none;
                    }
                    table.calendar .calendar-xs-content {
                        display: none;
                    }
                }
                </style>
            </div>
            <div class="col-md-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>

<?php get_footer() ?>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('.calendar-xs-title').on('click', function(e){
        $(this).closest('.row').find('.calendar-xs-content').slideToggle();
    });
});
</script>
<?php
// don't load directly
defined( 'ABSPATH' ) || die( '-1' );
class PIE_Admin_Notices extends PIE_Abstracts_Notices{
    function __construct()
    {
        add_action('admin_init' , array($this , 'mailchimp_addon_for_free'));
        add_action('admin_init' , array($this , 'launch_campaign_notice'));

        parent::__construct();
    }

    function launch_campaign_notice (){
        $notices_url = "https://pieforms.com/plan-and-pricing/?utm_source=admindashboard&utm_medium=notification&utm_campaign=launchoffer";

        $message =  sprintf("<div class='notice-launch-info'>");
        
        $message .=  sprintf("<div class='launch-icon'><img src='".Pie_Forms::$url. "assets/images/speaker.png' alt='speaker'></div>");

        $message .=  sprintf("<div class='launch-info'><h2>We are delighted to announce the Launch of the Pie Forms Premium Plan at an Exciting Price of $19.99!</h2>");

        $message .= sprintf("<p>Since our launch is just in time for the <em>Holidays</em>, here is a little surprise for our First time Premium Users:</p>");

        $message .= sprintf("<p>Buy the Pie Forms Premium Plan within the next 30 days and <em><span class='underline'>Get a Life Time Access to all our Premium Features and Updates,</spam></em> with No Additional or Hidden Charges!</p>");

        $message .= sprintf("<p>It's a bargain at <em>$19.99 and will not last long!</em></p>");
        
        $message .= sprintf("<a href=%s target='_blank'>Buy the Pie Forms Premium Plan NOW</a></div>" , $notices_url);
        
        $message .= sprintf("</div>");
  
        $this->info( $message , true);
        
    }
    function mailchimp_addon_for_free (){
        
        $notices_url = "https://pieforms.com/mailchimp-add-on/?utm_source=admindasboard&utm_medium=notification&utm_campaign=mailchimpfree";

        $message =  sprintf("<div class='notice-launch-info mailchimp'>");
        
        $message .=  sprintf("<div class='launch-icon mailchimp'><img src='".Pie_Forms::$url. "assets/images/pieforms-logo.png' alt='pieforms-logo'></div>");

        $message .=  sprintf("<div class='launch-info mailchimp'><h2>Get MailChimp Add-on for free</h2>");

        $message .= sprintf("<p>Limited time offer.<a href=%s target='_blank'> Click here </a>to get yours</p></div>" , $notices_url);
        
        $message .= sprintf("</div>");
  
        $this->info( $message , true);
        
    }
}
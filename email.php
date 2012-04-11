<?php
// Business tier class that manages Today astrological functionality

class MyEmail
{
	private $_Page = array();
	private $_TodaysChartInput = array();
	private $_wpdb;
	private $_current_user_id = array();
	private $_current_user_subscribed_posts;
	const BLOG_COMMENT_STRING = 'blog_comment';
	const EMAIL_TABLE_STRING = 'ask_email';
	const REMOVE_BLOG_COMMENT_NONCE_STRING = 'remove_blog_comments_email_subscription_';
	const ADD_BLOG_COMMENT_NONCE_STRING = 'add_blog_comments_email_subscription_';
	const EMAIL_SETTINGS_URL_STRING = 'settings/notifications/';
	
	public function __construct()
	{
		global $wpdb;
		$current_user = wp_get_current_user();

		$this->_wpdb = &$wpdb;
		$this->_current_user_id = $current_user->ID;
//$profileuser = get_userdata($user_id);

	}

	public function __get($key)
	{
        if (array_key_exists($key, $this->_Page))
			echo $this->_Page[$key];
    }
	
	public function __set($key, $value)
	{
		$this->_Page[$key] = $value;
    }

	private function isSubscribedToPost($post_id)
	{
		if( empty( $this->_current_user_subscribed_posts ) )
			$this->_current_user_subscribed_posts = $this->_wpdb->get_col('SELECT item_id FROM ' . self::EMAIL_TABLE_STRING . ' WHERE type = "' . self::BLOG_COMMENT_STRING . '" AND user_id = ' . $this->_current_user_id);

		return in_array($post_id, $this->_current_user_subscribed_posts );
	}
	public function showPostSubscriptionByEmail( $post_id )
	{
		$html = array();
		$html['no'] = '<input type="checkbox" checked="checked" id="my_comment_subscribe" name="my_comment_subscribe" class="checkbox" /><label for="my_comment_subscribe">Notify me of followup comments via e-mail. You can also subscribe without commenting.</label>';
		$html['yes'] = 'You are currently recieving followup comments by email for this page. <a href="' . $this->getRemovePostSubscriptionURL( $post_id ) . '">Unsubscribe</a>.';

		if( $this->isSubscribedToPost( $post_id ) )
			echo $html['yes'];
		else echo $html['no'];
	}
	public function addPostSubscription( $post_id )
	{
		// Did this visitor request to be subscribed to the discussion? (and s/he is not subscribed)
		if (!empty($_POST['my_comment_subscribe']) )
			$this->_wpdb->insert( self::EMAIL_TABLE_STRING, array( 'user_id' => $this->_current_user_id, 'type' => self::BLOG_COMMENT_STRING, 'item_id' => $post_id ), array( '%d', '%s', '%d' ) );

	}
	public function removePostSubscription( $post_id )
	{
		$nonce = self::REMOVE_BLOG_COMMENT_NONCE_STRING . $post_id;

		if( !wp_verify_nonce( $_REQUEST['_wpnonce'], $nonce ) )
			return;

		// Did this visitor request to be subscribed to the discussion? (and s/he is not subscribed)
		$this->_wpdb->query('DELETE FROM ' . self::EMAIL_TABLE_STRING . ' WHERE user_id = ' . $this->_current_user_id . ' AND item_id = ' . $post_id . ' AND type = "' . self::BLOG_COMMENT_STRING . '"');

		$this->showNotification( 'remove', $post_id );


	}
	public function addPostSubscriptionByURL( $post_id )
	{
		$nonce = self::ADD_BLOG_COMMENT_NONCE_STRING . $post_id;

		if( !wp_verify_nonce( $_REQUEST['_wpnonce'], $nonce ) )
			return;

		// Did this visitor request to be subscribed to the discussion? (and s/he is not subscribed)
			$this->_wpdb->insert( self::EMAIL_TABLE_STRING, array( 'user_id' => $this->_current_user_id, 'type' => self::BLOG_COMMENT_STRING, 'item_id' => $post_id ), array( '%d', '%s', '%d' ) );

		$this->showNotification( 'add', $post_id );

	}
	private function showNotification($type, $post_id)
	{
		$url = get_permalink( $post_id );
		$page = get_post( $post_id );
		$link = '<a href="' . $url . '">' . $page->post_title . '</a>';

		if( $type == 'remove' )
			$text = 'You will no longer receive comment updates from ' . $link;
		else if( $type == 'add' )
			$text = 'You will now receive comment updates from ' . $link;

		$html = array();
		$html[] = '<div id="message" class="notification">';
		$html[] = $text;
		$html[] = '</div>';
		echo join( '', $html );
	}
	public function getRemovePostSubscriptionURL( $post_id )
	{
		$url = bp_get_loggedin_user_link();
		$url .= self::EMAIL_SETTINGS_URL_STRING . "?action=remove&post_id=$post_id";
		$nonce = self::REMOVE_BLOG_COMMENT_NONCE_STRING . $post_id;


		return wp_nonce_url( $url, $nonce );

	}
	public function showPostSubscriptionByEmailURL( $post_id )
	{
		$url = bp_get_loggedin_user_link();
		$url .= self::EMAIL_SETTINGS_URL_STRING . "?action=add&post_id=$post_id";
		$nonce = self::ADD_BLOG_COMMENT_NONCE_STRING . $post_id;

		$link = '<a href="' . wp_nonce_url( $url, $nonce ) . '">Get Page Comment Updates in Email</a> | ';

		if( !$this->isSubscribedToPost( $post_id ) )
			echo $link;
	}

	public function getUserEmailSettingsURL()
	{
		$url = bp_get_loggedin_user_link();
		$url .= self::EMAIL_SETTINGS_URL_STRING;
		return $url;
	}
	public function showUserEmailSettingsURL()
	{
		echo $this->getUserEmailSettingsURL();
	}
	public function getSubscribedUsers($post_id)
	{
		$users = $this->_wpdb->get_col('SELECT user_id FROM ' . self::EMAIL_TABLE_STRING . ' WHERE type = "' . self::BLOG_COMMENT_STRING . '" AND item_id = ' . $post_id);

		if( empty( $users ) )
			return false;

		cache_users( $users );

		return $users;
	}
	public function sendEmail( $to, $subject, $message, $log = array() )
	{
		$defaults = array(
		'from_id'              => 1,  
		'to_id'             => 1,     
		'type'             => 'unknown'
		);
		$r = wp_parse_args( $log, $defaults );
		extract( $r, EXTR_SKIP );

		if( wp_mail($to, $subject, $message) )
		{
			my_log_add( array(
				'user_id' => $from_id,
				'action' => $subject,
				'content' => $message,
				'component' => 'email',
				'item_id' => $to_id,
				'type' => $type
			) );
			return true;
		}

		return false;
	}
	public function newUserNotification( $user_id )
	{
		$user = new WP_User($user_id);

		$args = array();
		$args['type'] = 'registration';
		$args['user_login'] = stripslashes($user->user_login);
		$args['name'] = stripslashes($user->nickname);
		$to = stripslashes($user->user_email);

		$email = new MyEmailTemplate( $args );
		$message = $email->getEmailMessage();
		$subject = $email->getEmailSubject();

		$log = array(
		'from_id'              => 1,  
		'to_id'             => $user_id,     
		'type'             => 'register'
		);

		return $this->sendEmail($to, $subject, $message, $log);
	}
	public function paymentSuccessNotification( $user_id, $payment_data = array() )
	{
		$user = new WP_User($user_id);

		$args = array();
		$args['type'] = 'payment';
		$args['user_login'] = stripslashes($user->user_login);
		$args['name'] = stripslashes($user->nickname);

		$args['item_type'] = $payment_data['item_number'];
		$args['amount'] = $payment_data['payment_gross'];
		
		$cc = urldecode( $payment_data['payer_email'] );
		$to = stripslashes( $user->user_email );

		$email = new MyEmailTemplate( $args );
		$message = $email->getEmailMessage();
		$subject = $email->getEmailSubject();

		$log = array(
		'from_id'              => 1,  
		'to_id'             => $user_id,     
		'type'             => 'payment_confirmation'
		);
		
		if( $to != $cc )
			$this->sendEmail($cc, $subject, $message, $log);

		//$this->sendEmail('admin@ask-oracle.com', $subject, $message);
		
		return $this->sendEmail($to, $subject, $message, $log);
	}
	public function showPostSubscriptions()
	{
		$posts = $this->_wpdb->get_col('SELECT item_id FROM ' . self::EMAIL_TABLE_STRING . ' WHERE type = "' . self::BLOG_COMMENT_STRING . '" AND user_id = ' . $this->_current_user_id);

		if( empty( $posts ) )
		{
			echo '<p>You are not subscribed to any page.</p>';
			return;
		}

		$html = array();
		
		$args = array( 'include' => $posts, 'post_type' => 'page');	
		$subscribed_posts = get_posts($args);

		foreach( $subscribed_posts as $subscribed_post )
		{
			setup_postdata($subscribed_post);
			$html[] = '<p><a href="' . get_permalink($subscribed_post->ID) . '">';
			$html[] = get_the_title($subscribed_post->ID);
			$html[] = '</a>';
			$html[] = ' <small><a href="' . $this->getRemovePostSubscriptionURL($subscribed_post->ID) ;
			$html[] = '">Remove</a></small></p>';
		}

		echo join( '', $html );
		
	}

}
class MyEmailTemplate
{
	private $_message;
	private $_subject;
	
	public function __construct( $args = array() )
	{
		switch ( $args['type'] )
		{
			case "registration":
				$this->registrationNotification( $args );
				break;
			case "payment":
				$this->paymentNotification( $args );
				break;
		}
	}
	public function getEmailMessage()
	{
		return $this->_message;
	}
	public function getEmailSubject()
	{
		return $this->_subject;
	}
	public function registrationNotification( $args )
	{
		$subject = 'Your Ask-Oracle.com Registration';

		$message  = sprintf(__('Dear %s'), $args['name']) . "\r\n\r\n";
		$message .= "Thank you for creating a User Account with Ask-Oracle.com\r\n\r\n";
		$message .= "Retain this account information for your records.\r\n";
		$message .= sprintf(__('Login ID: %s'), $args['user_login']) . "\r\n\r\n";
		$message .= "To access and manage your new account, please visit - " . wp_login_url() . " \r\n";
		$message .= "Login with your details to generate birth charts, see future predictions, interact with other users and so much more!\r\n\r\n";
//		$message .= 'If you want to reset your password, click "Account Settings" in "My Account," and then "Account Security Information."\r\n\r\n';
//		$message .= 'To reset your password, click the "Forgot Password?" link in the login area on the home page."\r\n\r\n';
		$message .= "Please let us know if you have any further questions, comments, or concerns by replying to this email.\r\n\r\n";
		$message .= "Wishes,\r\n";
		$message .= "Ask-Oracle.com";

		$this->_subject = $subject;
		$this->_message = $message;
	}
	public function paymentNotification( $args )
	{
		$amount = '$' . $args['amount'];

		switch ( $args['item_type'] )
		{
			case "MR":
				$product_name = 'When Will You Marry Report';
				$product_url = get_linkTO( 'when-marry/' );
				break;
			case "FR":
				$product_name = 'Future Predictions - General Trends and Analysis Report';
				$product_url = get_linkTO('birth-chart/future/');
				break;
		}
		$subject = 'Payment Received. Your Report is Ready!';

		$message  = sprintf(__('Dear %s'), $args['name']) . "\r\n\r\n";
		$message .= "Thank you for your order. We've received your payment of $amount for $product_name. \r\n\r\n";
		$message .= "Your report is ready and can be accessed here - $product_url \r\n\r\n";
		$message .= sprintf(__('Login ID: %s'), $args['user_login']) . "\r\n\r\n";
		$message .= "To generate your report, please follow these easy steps: \r\n";
		$message .= "1) Log into your Ask-Oracle.com account - " . wp_login_url() . " \r\n";
		$message .= "2) Save and Generate your birth chart - " . get_linkTO( 'birth-chart/' ) . " \r\n";
		$message .= "3) When it shows your birth chart, you can see your report online here - $product_url \r\n\r\n";
//		$message .= 'If you want to reset your password, click "Account Settings" in "My Account," and then "Account Security Information."\r\n\r\n';
//		$message .= 'To reset your password, click the "Forgot Password?" link in the login area on the home page."\r\n\r\n';
		$message .= "Please let us know if you have any further questions, comments, or concerns by replying to this email.\r\n\r\n";
		$message .= "Wishes,\r\n";
		$message .= "Ask-Oracle.com";

		$this->_subject = $subject;
		$this->_message = $message;
	}

}

?>

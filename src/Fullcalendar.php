<?php

namespace edofre\fullcalendar;

/**
 * Class Fullcalendar
 * @package edofre\fullcalendar
 */
class Fullcalendar extends \yii\base\Widget
{
	/**
	 * @var array  The fullcalendar options, for all available options check http://fullcalendar.io/docs/
	 */
	public $clientOptions = [
		'weekends' => true,
		'default'  => 'agendaDay',
		'editable' => false,
	];
	/**
	 * @var array  Array containing the events, can be JSON array, PHP array or URL that returns an array containing JSON events
	 */
	public $events = [];
	/** @var boolean  Determines whether or not to include the gcal.js */
	public $googleCalendar = false;
	/**
	 * @var array
	 * Possible header keys
	 * - center
	 * - left
	 * - right
	 * Possible options:
	 * - title
	 * - prevYear
	 * - nextYear
	 * - prev
	 * - next
	 * - today
	 * - basicDay
	 * - agendaDay
	 * - basicWeek
	 * - agendaWeek
	 * - month
	 */
	public $header = [
		'center' => 'title',
		'left'   => 'prev,next, today',
		'right'  => 'agendaDay,agendaWeek,month',
	];
	/** @var string  Text to display while the calendar is loading */
	public $loading = 'Please wait, calendar is loading';
	/**
	 * @var array  Default options for the id and class HTML attributes
	 */
	public $options = [
		'id'    => 'calendar',
		'class' => 'fullcalendar',
	];
	/**
	 * @var boolean  Whether or not we need to include the ThemeAsset bundle
	 */
	public $theme = false;


	public $themeSystem;

	
	/**
     * The javascript function to us as en eventRender callback
     * @var string the javascript code that implements the eventRender function
     */
    public $eventRender = "";

    /**
     * The javascript function to us as en eventAfterRender callback
     * @var string the javascript code that implements the eventAfterRender function
     */
    public $eventAfterRender = "";

    /**
     * The javascript function to us as en eventAfterAllRender callback
     * @var string the javascript code that implements the eventAfterAllRender function
     */
    public $eventAfterAllRender = "";

     /**
     * The javascript function to us as en eventDrop callback
     * @var string the javascript code that implements the eventDrop function
     */

    public $eventDrop = "";
	
     /**
     * The javascript function to us as en eventResize callback
     * @var string the javascript code that implements the eventResize function
     */

    public $eventResize = "";

    /**
     * A js callback that triggered when the user clicks an event.
     * @var string the javascript code that implements the eventClick function
     */
    public $eventClick = "";

    /**
     * A js callback that triggered when the user clicks an day.
     * @var string the javascript code that implements the dateClick function
     */
    public $dateClick = "";

    /**
     * A js callback that will fire after a selection is made.
     * @var string the javascript code that implements the select function
     */
    public $select = "";

	/**
	 * Always make sure we have a valid id and class for the Fullcalendar widget
	 */
	public function init()
	{
		if (!isset($this->options['id'])) {
			$this->options['id'] = $this->getId();
		}
		if (!isset($this->options['class'])) {
			$this->options['class'] = 'fullcalendar';
		}

		parent::init();
	}

	/**
	 * Load the options and start the widget
	 */
	public function run()
	{
		$this->echoLoadingTags();

		$assets = CoreAsset::register($this->view);

		if ($this->theme === true) { // Register the theme
			ThemeAsset::register($this->view);
		}

		if (isset($this->options['language'])) {
			$assets->language = $this->options['language'];
		}

		$assets->googleCalendar = $this->googleCalendar;
		$this->clientOptions['header'] = $this->header;

		$this->view->registerJs(implode("\n", [
			"jQuery('#{$this->options['id']}').fullCalendar({$this->getClientOptions()});",
		]), \yii\web\View::POS_READY);
	}

	/**
	 * Echo the tags to show the loading state for the calendar
	 */
	private function echoLoadingTags()
	{
		echo \yii\helpers\Html::beginTag('div', $this->options) . "\n";
		echo \yii\helpers\Html::beginTag('div', ['class' => 'fc-loading', 'style' => 'display:none;']);
		echo \yii\helpers\Html::encode($this->loading);
		echo \yii\helpers\Html::endTag('div') . "\n";
		echo \yii\helpers\Html::endTag('div') . "\n";
	}

	/**
	 * @return string
	 * Returns an JSON array containing the fullcalendar options,
	 * all available callbacks will be wrapped in JsExpressions objects if they're set
	 */
	private function getClientOptions()
	{
		$options['loading'] = new \yii\web\JsExpression("function(isLoading, view ) {
			jQuery('#{$this->options['id']}').find('.fc-loading').toggle(isLoading);
		}");
		

		//add new theme information for the calendar                                       
		$options['themeSystem'] = $this->themeSystem;
                                               
        if ($this->eventRender){
            $options['eventRender'] = new JsExpression($this->eventRender);
        }
        if ($this->eventAfterRender){
            $options['eventAfterRender'] = new JsExpression($this->eventAfterRender);
        }
        if ($this->eventAfterAllRender){
            $options['eventAfterAllRender'] = new JsExpression($this->eventAfterAllRender);
        }

       if ($this->eventDrop){
            $options['eventDrop'] = new JsExpression($this->eventDrop);
        }
	    
        if ($this->eventResize){
            $options['eventResize'] = new JsExpression($this->eventResize);
        }	    

        if ($this->select){
            $options['select'] = new JsExpression($this->select);
        }
                                               
        if ($this->eventClick){
            $options['eventClick'] = new JsExpression($this->eventClick);
        }
        if ($this->dateClick){
            $options['dateClick'] = new JsExpression($this->dateClick);
        }


		$options['events'] = $this->events;
		$options = array_merge($options, $this->clientOptions);

		return \yii\helpers\Json::encode($options);
	}
}

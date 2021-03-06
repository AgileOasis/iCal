<?php

/*
 * This file is part of the eluceo/iCal package.
 *
 * (c) Markus Poerschke <markus@eluceo.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eluceo\iCal\Component;

use Eluceo\iCal\Component;
use Eluceo\iCal\PropertyBag;

class Calendar extends Component
{
    /**
     * Methods for calendar components
     * 
     * According to RFP 5545: 3.7.2. Method
     * 
     * @link http://tools.ietf.org/html/rfc5545#section-3.7.2
     * 
     * And then according to RFC 2446: 3 APPLICATION PROTOCOL ELEMENTS
     * 
     * @link https://www.ietf.org/rfc/rfc2446.txt
     */
    const METHOD_PUBLISH = 'PUBLISH';
    const METHOD_REQUEST = 'REQUEST';
    const METHOD_REPLY = 'REPLY';
    const METHOD_ADD = 'ADD';
    const METHOD_CANCEL = 'CANCEL';
    const METHOD_REFRESH = 'REFRESH';
    const METHOD_COUNTER = 'COUNTER';
    const METHOD_DECLINECOUNTER = 'DECLINECOUNTER';

    /**
     * This property defines the calendar scale used for the calendar information specified in the iCalendar object.
     * 
     * According to RFC 5545: 3.7.1. Calendar Scale
     * 
     * @link http://tools.ietf.org/html/rfc5545#section-3.7
     */
    const CALSCALE_GREGORIAN = 'GREGORIAN';

    /**
     * The Product Identifier
     *
     * According to RFC 2445: 4.7.3 Product Identifier
     *
     * This property specifies the identifier for the product that created the Calendar object.
     *
     * @link http://www.ietf.org/rfc/rfc2445.txt
     *
     * @var string
     */
    protected $prodId = null;
    protected $method = null;
    protected $name = null;
    protected $description = null;
    protected $timezone = null;
    protected $calendarScale = null;

    public function __construct($prodId)
    {
        if (empty($prodId)) {
            throw new \UnexpectedValueException('PRODID cannot be empty');
        }

        $this->prodId = $prodId;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'VCALENDAR';
    }

    /**
     * @param $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param $timezone
     * @return $this
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @param $calendarScale
     * @return $this
     */
    public function setCalendarScale($calendarScale)
    {
        $this->calendarScale = $calendarScale;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPropertyBag()
    {
        $this->properties = new PropertyBag;
        $this->properties->set('VERSION', '2.0');
        $this->properties->set('PRODID', $this->prodId);

        if ($this->method) {
            $this->properties->set('METHOD', $this->method);
        }

        if ($this->calendarScale) {
            $this->properties->set('CALSCALE', $this->calendarScale);
        }

        if ($this->name) {
            $this->properties->set('X-WR-CALNAME', $this->name);
        }

        if ($this->description) {
            $this->properties->set('X-WR-CALDESC', $this->description);
        }

        if ($this->timezone) {
            $this->properties->set('X-WR-TIMEZONE', $this->timezone);
            $this->addComponent(new Timezone($this->timezone));
        }
    }

    /**
     * Adds an Event to the Calendar
     *
     * Wrapper for addComponent()
     *
     * @see Eluceo\iCal::addComponent
     * @deprecated Please, use public method addComponent() from abstract Component class
     *
     * @param Event $event
     */
    public function addEvent(Event $event)
    {
        $this->addComponent($event);
    }

    /**
     * @return null|string
     */
    public function getProdId()
    {
        return $this->prodId;
    }
}

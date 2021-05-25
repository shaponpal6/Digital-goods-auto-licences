<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Domain\Entity\Payment;

use AmeliaBooking\Domain\ValueObjects\DateTime\DateTimeValue;
use AmeliaBooking\Domain\ValueObjects\Number\Float\Price;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\Id;
use AmeliaBooking\Domain\ValueObjects\String\Name;
use AmeliaBooking\Domain\ValueObjects\String\PaymentStatus;
use AmeliaBooking\Domain\ValueObjects\String\PaymentData;

/**
 * Class Payment
 *
 * @package AmeliaBooking\Domain\Entity\Payment
 */
class Payment
{
    /** @var Id */
    private $id;

    /** @var  Id */
    private $customerBookingId;

    /** @var  Id */
    private $packageCustomerId;

    /** @var  Price */
    private $amount;

    /** @var  DateTimeValue */
    private $dateTime;

    /** @var  PaymentStatus */
    private $status;

    /** @var  PaymentGateway */
    private $gateway;

    /** @var  Name */
    private $gatewayTitle;

    /** @var PaymentData */
    private $data;

    /**
     * Payment constructor.
     *
     * @param Id             $customerBookingId
     * @param Price          $amount
     * @param DateTimeValue  $dateTime
     * @param PaymentStatus  $status
     * @param PaymentGateway $gateway
     * @param PaymentData    $data
     */
    public function __construct(
        Id $customerBookingId,
        Price $amount,
        DateTimeValue $dateTime,
        PaymentStatus $status,
        PaymentGateway $gateway,
        PaymentData $data
    ) {
        $this->customerBookingId = $customerBookingId;
        $this->amount = $amount;
        $this->dateTime = $dateTime;
        $this->status = $status;
        $this->gateway = $gateway;
        $this->data = $data;
    }

    /**
     * @return Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Id $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Id
     */
    public function getPackageCustomerId()
    {
        return $this->packageCustomerId;
    }

    /**
     * @param Id $packageCustomerId
     */
    public function setPackageCustomerId($packageCustomerId)
    {
        $this->packageCustomerId = $packageCustomerId;
    }

    /**
     * @return Price
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param Price $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return DateTimeValue
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param DateTimeValue $dateTime
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return PaymentStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param PaymentStatus $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return PaymentGateway
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param PaymentGateway $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return Name
     */
    public function getGatewayTitle()
    {
        return $this->gatewayTitle;
    }

    /**
     * @param Name $gatewayTitle
     */
    public function setGatewayTitle($gatewayTitle)
    {
        $this->gatewayTitle = $gatewayTitle;
    }

    /**
     * @return PaymentData
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param PaymentData $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'                => null !== $this->getId() ? $this->getId()->getValue() : null,
            'customerBookingId' => $this->customerBookingId->getValue(),
            'packageCustomerId' => $this->packageCustomerId ? $this->packageCustomerId->getValue() : null,
            'amount'            => $this->amount->getValue(),
            'gateway'           => $this->gateway->getName()->getValue(),
            'gatewayTitle'      => null !== $this->getGatewayTitle() ? $this->getGatewayTitle()->getValue() : '',
            'dateTime'          => null !== $this->dateTime ? $this->dateTime->getValue()->format('Y-m-d H:i:s') : null,
            'status'            => $this->status->getValue(),
            'data'              => $this->data->getValue(),
        ];
    }
}

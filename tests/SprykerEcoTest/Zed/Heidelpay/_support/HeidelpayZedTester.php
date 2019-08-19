<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\Heidelpay;

use Codeception\Actor;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class HeidelpayZedTester extends Actor
{
    use _generated\HeidelpayZedTesterActions;

    protected const NOTIFICATION_FILE_PATH = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'notification_body.xml';

   /**
    * Define custom actions here
    */

    /**
     * @return string
     */
   public function getNotificationBody(): string
   {
       $xml = simplexml_load_file(static::NOTIFICATION_FILE_PATH);

       return $xml->asXML();
   }
}

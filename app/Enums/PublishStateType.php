<?php declare(strict_types=1);

namespace App\Enums;

// use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PublishStateType extends Enum
{
    const J1 = 0;
    const J2 = 1;
    const J3 = 2;
    const error = 3;

    public static function getcateteam($cate){
        switch($cate){
            case self::J1:
            return "J1";
            break;
            case self::J2:
            return "J2";
            break;
            case self::J3:
            return "J3";
            break;
            default:
            return "nocate";
            break;
        }
    }

}

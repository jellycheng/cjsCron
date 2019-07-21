<?php
namespace CjsCron;

/**
 * Abstract CRON expression field
 */
abstract class AbstractField implements FieldInterface
{
    /**
     * Check to see if a field is satisfied by a value
     * 是否满足条件
     * @param string $dateValue Date value to check 当前时间值
     * @param string $value     Value to test  设置的范围值
     *
     * @return bool
     */
    public function isSatisfied($dateValue, $value)
    {
        if ($this->isIncrementsOfRanges($value)) {//是指定增量 */5、5/10
            return $this->isInIncrementsOfRanges($dateValue, $value);
        } elseif ($this->isRange($value)) {//是指定范围，1-18
            return $this->isInRange($dateValue, $value);
        }

        return $value == '*' || $dateValue == $value;
    }

    /**
     * Check if a value is a range
     * 是否指定范围
     * @param string $value Value to test
     *
     * @return bool
     */
    public function isRange($value)
    {
        return strpos($value, '-') !== false;
    }

    /**
     * Check if a value is an increments of ranges
     * 是否指定增量
     * @param string $value Value to test
     *
     * @return bool
     */
    public function isIncrementsOfRanges($value)
    {
        return strpos($value, '/') !== false;
    }

    /**
     * Test if a value is within a range
     * 是否在指定范围内
     * @param string $dateValue Set date value
     * @param string $value     Value to test
     *
     * @return bool
     */
    public function isInRange($dateValue, $value)
    {
        $parts = array_map('trim', explode('-', $value, 2));// 1-5

        return $dateValue >= $parts[0] && $dateValue <= $parts[1];
    }

    /**
     * Test if a value is within an increments of ranges (offset[-to]/step size)
     * 是否在指定增量内
     * @param string $dateValue Set date value
     * @param string $value     Value to test
     *
     * @return bool
     */
    public function isInIncrementsOfRanges($dateValue, $value)
    {
        $parts = array_map('trim', explode('/', $value, 2));
        $stepSize = isset($parts[1]) ? $parts[1] : 0; //步长，间隔值
        if (($parts[0] == '*' || $parts[0] === '0') && 0 !== $stepSize) {// 0/5、*/5
            return (int) $dateValue % $stepSize == 0;
        }

        $range = explode('-', $parts[0], 2);
        $offset = $range[0];
        $to = isset($range[1]) ? $range[1] : $dateValue;
        // Ensure that the date value is within the range
        if ($dateValue < $offset || $dateValue > $to) {
            return false;
        }

        if ($dateValue > $offset && 0 === $stepSize) {
          return false;
        }

        for ($i = $offset; $i <= $to; $i+= $stepSize) {
            if ($i == $dateValue) {
                return true;
            }
        }

        return false;
    }
}

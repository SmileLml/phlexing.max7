<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Style;

/**
 * Output style helpers.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface StyleInterface
{
    /**
     * Formats a command title.
     *
     * @return void
     * @param string $message
     */
    public function title($message);

    /**
     * Formats a section title.
     *
     * @return void
     * @param string $message
     */
    public function section($message);

    /**
     * Formats a list.
     *
     * @return void
     * @param mixed[] $elements
     */
    public function listing($elements);

    /**
     * Formats informational text.
     *
     * @return void
     * @param string|mixed[] $message
     */
    public function text($message);

    /**
     * Formats a success result bar.
     *
     * @return void
     * @param string|mixed[] $message
     */
    public function success($message);

    /**
     * Formats an error result bar.
     *
     * @return void
     * @param string|mixed[] $message
     */
    public function error($message);

    /**
     * Formats an warning result bar.
     *
     * @return void
     * @param string|mixed[] $message
     */
    public function warning($message);

    /**
     * Formats a note admonition.
     *
     * @return void
     * @param string|mixed[] $message
     */
    public function note($message);

    /**
     * Formats a caution admonition.
     *
     * @return void
     * @param string|mixed[] $message
     */
    public function caution($message);

    /**
     * Formats a table.
     *
     * @return void
     * @param mixed[] $headers
     * @param mixed[] $rows
     */
    public function table($headers, $rows);

    /**
     * Asks a question.
     * @return mixed
     * @param string $question
     * @param string|null $default
     * @param callable|null $validator
     */
    public function ask($question, $default = null, $validator = null);

    /**
     * Asks a question with the user input hidden.
     * @return mixed
     * @param string $question
     * @param callable|null $validator
     */
    public function askHidden($question, $validator = null);

    /**
     * Asks for confirmation.
     * @param string $question
     * @param bool $default
     */
    public function confirm($question, $default = true);

    /**
     * Asks a choice question.
     * @param mixed $default
     * @return mixed
     * @param string $question
     * @param mixed[] $choices
     */
    public function choice($question, $choices, $default = null);

    /**
     * Add newline(s).
     *
     * @return void
     * @param int $count
     */
    public function newLine($count = 1);

    /**
     * Starts the progress output.
     *
     * @return void
     * @param int $max
     */
    public function progressStart($max = 0);

    /**
     * Advances the progress output X steps.
     *
     * @return void
     * @param int $step
     */
    public function progressAdvance($step = 1);

    /**
     * Finishes the progress output.
     *
     * @return void
     */
    public function progressFinish();
}

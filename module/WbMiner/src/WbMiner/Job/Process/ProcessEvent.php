<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job\Process;


class ProcessEvent
{
    const PROCESS_POST = 'process.post';

    const PROCESS_FAILED = 'process.failed';

    const PROCESS = 'process';

    const PROCESS_EXCEPTION = 'process.exception';

    const PROCESS_STOPPED = 'process.stopped';

    const PROCESS_FINISHED = 'process.finished';

}
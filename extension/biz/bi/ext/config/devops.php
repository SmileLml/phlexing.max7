<?php
$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计代码评审问题数',
    'alias'      => '系统代码评审问题数',
    'code'       => 'count_of_issue',
    'purpose'    => 'qc',
    'scope'      => 'system',
    'object'     => 'codebase',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计代码评审问题数是指代码评审所有的问题数，该度量项可以帮助团队及时识别代码中的潜在缺陷，从而提高代码质量和团队的整体开发效率。',
    'definition' => "代码评审所有的问题数\n不统计删除的问题\n不统计删除的代码库里的问题"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的上线次数',
    'alias'      => '系统上线数',
    'code'       => 'count_of_finished_deployment',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'deployment',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的上线次次数是指在一定时间内的进行上线的上线申请数量，反映了团队的快速迭代和持续交付的能力，较高的上线频率意味着团队能够更快地将新功能、修复或改进的版本交付给用户，实现更加灵活和快速的交付周期。',
    'definition' => "系统的上线中/上线成功/上线失败的上线申请个数求和\n不统计已删除上线申请\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计待处理的上线计划总数',
    'alias'      => '待处理的上线计划数',
    'code'       => 'count_of_pending_deployment',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'deployment',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '按系统统计的待处理的上线计划总数是指所有尚未完成的计划数量。该度量反映了团队在软件交付和发布管理方面的任务积压和工作进展情况。',
    'definition' => "所有的未完成的上线计划个数求和 \n不统计已删除上线申请\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的上线成功数',
    'alias'      => '系统上线成功数',
    'code'       => 'count_of_success_deployment',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'deployment',
    'unit'       => 'count',
    'dateType'   => 'nodate',
    'desc'       => '上线成功数是衡量团队在软件发布过程中的绩效和交付能力的重要指标。通过统计在一定时间范围内成功完成的上线操作数量，团队能够评估其发布流程的有效性和稳定性，及时识别问题并优化上线策略。',
    'definition' => "系统的上线成功的上线申请个数求和 \n不统计已删除上线申请\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的上线准备平均时长',
    'alias'      => '系统上线准备平均时长',
    'code'       => 'avg_duration_of_ready_deployment',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'deployment',
    'unit'       => 'hour',
    'dateType'   => 'nodate',
    'desc'       => '上线成功数是衡量团队在软件发布过程中的绩效和交付能力的重要指标。通过统计在一定时间范围内成功完成的上线操作数量，团队能够评估其发布流程的有效性和稳定性，及时识别问题并优化上线策略。',
    'definition' => "系统的上线成功的上线申请个数求和 \n不统计已删除上线申请\n"
);

$config->bi->builtin->metrics[] = array
(
    'name'       => '按系统统计的上线成功率',
    'alias'      => '系统上线成功率',
    'code'       => 'rate_of_success_deployment',
    'purpose'    => 'rate',
    'scope'      => 'system',
    'object'     => 'deployment',
    'unit'       => 'percentage',
    'dateType'   => 'nodate',
    'desc'       => '上线成功率是衡量团队在软件发布过程中的稳定性和可靠性的重要指标。通过统计上线成功率，团队能够评估其发布流程的有效性，及时识别潜在问题并优化上线策略。',
    'definition' => "系统的上线成功的上线申请数量/（上线中/上线成功/上线失败的上线申请个数）  \n不统计已删除上线申请\n"
);

alter table `zt_im_conference` modify `status` enum ('closed', 'open', 'notStarted', 'canceled') default 'closed' not null;

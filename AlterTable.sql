
ALTER TABLE songcircle_create 
add COLUMN songcircle_status int(11)  DEFAULT '0' COMMENT '0 Not started\n	1 Started\n	5 completed' 
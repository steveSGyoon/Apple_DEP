create table t_login_try (
	idx int unsigned NOT NULL auto_increment,
	user_id varchar(128),
	is_valid tinyint unsigned default 1,
	PRIMARY KEY (idx)
);
alter table t_login_try add index user_id(user_id);
alter table t_login_try add index is_valid(is_valid);



create table t_user (
	idx int unsigned NOT NULL auto_increment,
	user_id varchar(128) NOT NULL,
	user_password varchar(255) NOT NULL,
	user_name varchar(32) NOT NULL,
	_OR tinyint unsigned default 0,		-- ordeer
	_RE tinyint unsigned default 0,		-- return
	_VO tinyint unsigned default 0,		-- void
	_OV tinyint unsigned default 0,		-- override
	_LU tinyint unsigned default 0,		-- lookup
	_DCM tinyint unsigned default 0,	-- DEP customer management
	_DUM tinyint unsigned default 0,	-- DEP user management
	_ADMIN tinyint unsigned default 0,	-- admin = superuser
	insert_date datetime default CURRENT_TIMESTAMP,
	edit_info_date datetime,
	edit_permission_date datetime,
	is_valid tinyint default 1,
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
alter table t_user add index user_id(user_id);
alter table t_user add index user_name(user_name);
alter table t_user add index is_valid(is_valid);



create table t_customer (
	idx int unsigned NOT NULL auto_increment,
	dep_customer_id varchar(16) NOT NULL,
	skn_customer_id varchar(16) NOT NULL,
	company varchar(64) NOT NULL,
	phone varchar(16),
	email varchar(255),
	charge varchar(64),
	note text,
	insert_date datetime default CURRENT_TIMESTAMP,
	edit_date datetime,
	is_valid tinyint default 1,
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
alter table t_customer add index dep_customer_id(dep_customer_id);
alter table t_customer add index skn_customer_id(skn_customer_id);
alter table t_customer add index is_valid(is_valid);




create table t_order (
	idx int unsigned NOT NULL auto_increment,
	order_number varchar(32) NOT NULL,
	transaction_id varchar(64) NOT NULL,
	order_type varchar(8) NOT NULL,		-- _OR / _RE / _OV
	dep_customer_id varchar(16) NOT NULL,
	dep_reseller_id varchar(32) NOT NULL,
	ship_to varchar(32) NOT NULL,
	po_number varchar(32),
	order_date timestamp,
	ship_date timestamp,
	status tinyint default 0,			-- 0:cretae, 1:applied, 2:completed
	is_void tinyint default 0,
	applied_date datetime,
	completed_date datetime,
	edit_date datetime,
	insert_date datetime default CURRENT_TIMESTAMP,
	is_valid tinyint default 1,
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
alter table t_order add index order_number(order_number);
alter table t_order add index transaction_id(transaction_id);
alter table t_order add index order_type(order_type);
alter table t_order add index status(status);
alter table t_order add index is_void(is_void);
alter table t_order add index is_valid(is_valid);

create table t_order_device (
	idx int unsigned NOT NULL auto_increment,
	t_order_idx int unsigned NOT NULL,
	delivery_number varchar(32) NOT NULL,
	device_id varchar(32) NOT NULL,
	asset_tag varchar(32),
	insert_date datetime default CURRENT_TIMESTAMP,
	is_valid tinyint default 1,
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
alter table t_order_device add index t_order_idx(t_order_idx);
alter table t_order_device add index delivery_number(delivery_number);

create table t_api_enroll_result (
	idx int unsigned NOT NULL auto_increment,
	t_order_idx int unsigned NOT NULL,
	is_success tinyint default 0,
	send_data text,
	response text,
    deviceEnrollmentTransactionId varchar(128),
	transactionId varchar(128),
	errorCode varchar(16),
	errorMessage text,
	insert_date datetime default CURRENT_TIMESTAMP,
	is_valid tinyint default 1,
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
alter table t_api_enroll_result add index t_order_idx(t_order_idx);
alter table t_api_enroll_result add index is_valid(is_valid);

create table t_api_check_result (
	idx int unsigned NOT NULL auto_increment,
	t_order_idx int unsigned NOT NULL,
	is_success tinyint default 0,
	send_data text,
	response text,
    deviceEnrollmentTransactionId varchar(128),
	completed_on timestamp,
	transactionId varchar(128),
	errorCode varchar(16),
	errorMessage text,
	insert_date datetime default CURRENT_TIMESTAMP,
	is_valid tinyint default 1,
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
alter table t_api_check_result add index t_order_idx(t_order_idx);
alter table t_api_check_result add index is_valid(is_valid);


create table t_api_detail_result (
	idx int unsigned NOT NULL auto_increment,
	t_order_idx int unsigned NOT NULL,
	send_data text,
	response text,
	is_success tinyint default 0,
    deviceEnrollmentTransactionId varchar(128),
	completed_on timestamp,
	transactionId varchar(128),
	errorCode varchar(16),
	errorMessage text,
	insert_date datetime default CURRENT_TIMESTAMP,
	is_valid tinyint default 1,
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
alter table t_api_detail_result add index t_order_idx(t_order_idx);
alter table t_api_detail_result add index is_valid(is_valid);


create table t_test (
	idx int unsigned NOT NULL auto_increment,
	insert_date datetime default CURRENT_TIMESTAMP,
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

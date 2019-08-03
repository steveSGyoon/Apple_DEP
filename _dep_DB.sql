
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
	shipTo varchar(32) NOT NULL,
	depResellerId varchar(32) NOT NULL,
	orderNumber varchar(32) NOT NULL,
	orderDate datetime,
	orderType varchar(8) NOT NULL,		-- _OR / _RE / _OV
	customerId varchar(16) NOT NULL,
	poNumber varchar(32),
	shipDate datetime,
	insert_date datetime default CURRENT_TIMESTAMP,
	is_valid tinyint default 1,
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
alter table t_order add index orderNumber(orderNumber);
alter table t_order add index customerId(customerId);
alter table t_order add index orderType(orderType);

create table t_order_device (
	idx int unsigned NOT NULL auto_increment,
	t_order_idx int unsigned NOT NULL,
	deliveryNumber varchar(32) NOT NULL,
	deviceId varchar(32) NOT NULL,
	assetTag varchar(32),
	insert_date datetime default CURRENT_TIMESTAMP,
	is_valid tinyint default 1,
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
alter table t_order_device add index t_order_idx(t_order_idx);



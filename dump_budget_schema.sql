DROP TABLE categories;
DROP TABLE configurations;
DROP TABLE expense_installments;
DROP TABLE expenses;
DROP TABLE users;

create table users(
	id serial not null primary key,
	name varchar(100) not null,
	email varchar(100) not null,
	password varchar(255) not null,
	created_at timestamp default now(),
	updated_at timestamp default now()
);

CREATE TABLE categories (
	id serial NOT NULL,
	description varchar(255) NOT NULL,
	belongs_to int4 NOT NULL,
	created_at timestamp NOT NULL DEFAULT now(),
	updated_at timestamp NOT NULL DEFAULT now(),
	CONSTRAINT categories_pkey PRIMARY KEY (id)
);

CREATE TABLE configurations (
	id serial NOT NULL,
	description varchar(255) NOT NULL,
	value varchar(255) NULL,
	belongs_to varchar(10) NOT NULL,
	user_id int4 NOT NULL,
	created_at timestamp NOT NULL DEFAULT now(),
	updated_at timestamp NOT NULL DEFAULT now(),
	CONSTRAINT configurations_pkey PRIMARY KEY (id)
);

CREATE TABLE expense_installments (
	id serial NOT NULL,
	expense int4 NOT NULL,
	hash_installment varchar(255) NOT NULL,
	created_at timestamp NULL DEFAULT now(),
	updated_at timestamp NULL DEFAULT now(),
	CONSTRAINT expense_installments_pkey PRIMARY KEY (id)
);

CREATE TABLE expenses (
	id serial NOT NULL,
	description varchar(255) NOT NULL,
	category int4 NOT NULL,
	installments int4 NOT NULL DEFAULT 1,
	installment int4 NOT NULL DEFAULT 1,
	"month" int4 NOT NULL,
	"year" int4 NOT NULL,
	"period" int4 NOT NULL DEFAULT 0,
	repeat_next_months int4 NOT NULL DEFAULT 0,
	budgeted_amount numeric(12, 2) NOT NULL,
	realized_amount numeric(12, 2) NOT NULL DEFAULT 0,
	user_id int4 NOT NULL,
	created_at timestamp NULL DEFAULT now(),
	updated_at timestamp NULL DEFAULT now(),
	cancelled int4 NOT NULL DEFAULT 0,
	reason_cancelled varchar(255) NULL,
	CONSTRAINT expenses_pkey PRIMARY KEY (id)
);

CREATE TABLE recipes (
	id serial NOT NULL,
	description varchar(255) NOT NULL,
	category int4 NOT NULL,
	"month" int4 NOT NULL,
	"year" int4 NOT NULL,
	repeat_next_months int4 NOT NULL DEFAULT 0,
	budgeted_amount numeric(12, 2) NOT NULL,
	user_id int4 NOT NULL,
	created_at timestamp NULL DEFAULT now(),
	updated_at timestamp NULL DEFAULT now(),
	CONSTRAINT recipes_pkey PRIMARY KEY (id)
);

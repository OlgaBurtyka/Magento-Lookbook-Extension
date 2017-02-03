-- add table prefix if you have one
DROP TABLE IF EXISTS siteway_lookbook_look_product;
DROP TABLE IF EXISTS siteway_lookbook_look_category;
DROP TABLE IF EXISTS siteway_lookbook_look;
DELETE FROM core_resource WHERE code = 'siteway_lookbook_setup';
DELETE FROM core_config_data WHERE path like 'siteway_lookbook/%';
CREATE TABLE rms_extlocate_extend_domain_model_ip_cache (
    hash_value varchar(32) NOT NULL DEFAULT '',
    ip varchar(255) NOT NULL DEFAULT '',
    country_name varchar(255) NOT NULL DEFAULT '',
    json_geodata text,
    KEY hash_value (hash_value)
);
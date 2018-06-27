CREATE TABLE messages
(
  `id`         VARCHAR(36)  NOT NULL
    PRIMARY KEY,
  `content`    TEXT         NOT NULL,
  `sent_at`    DATETIME     NOT NULL,
  `user_agent` TEXT         NULL,
  `user_ip`    INT UNSIGNED NULL,
  CONSTRAINT messages_id_uindex
  UNIQUE (`id`)
)
  ENGINE = InnoDB;


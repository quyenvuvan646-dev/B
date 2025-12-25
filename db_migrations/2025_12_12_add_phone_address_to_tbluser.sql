-- Adds phone and address columns to tbluser
ALTER TABLE tbluser
  ADD COLUMN phone VARCHAR(20) NULL AFTER fullname;

ALTER TABLE tbluser
  ADD COLUMN address VARCHAR(255) NULL AFTER phone;

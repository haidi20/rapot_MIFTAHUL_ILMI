-- CREATE VIEW view_absen
-- AS
	SELECT * 
	FROM absen
	LEFT JOIN absen_type ON absen_type.id = absen.absen_type_id
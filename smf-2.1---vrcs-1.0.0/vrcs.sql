DROP TRIGGER IF EXISTS smf_vrcs_posts;
CREATE TRIGGER smf_vrcs_posts BEFORE UPDATE ON smf_members FOR EACH ROW
BEGIN
	IF NEW.posts != OLD.posts THEN
		UPDATE
			smf_vrcs_stat ls0,
			smf_vrcs_link ll1
		SET
			ll1.posts_l1 = ll1.posts_l1 + NEW.posts - OLD.posts
		WHERE
			OLD.id_member = ls0.id_member AND
			ls0.id_referer = ll1.id_reflink
		;
		UPDATE
			smf_vrcs_stat ls0,
			smf_vrcs_link ll1,
			smf_vrcs_stat ls1,
			smf_vrcs_link ll2,
			smf_vrcs_stat ls2
		SET
			ls2.posts_l2 = ls2.posts_l2 + NEW.posts - OLD.posts
		WHERE
			OLD.id_member = ls0.id_member AND
			ls0.id_referer = ll1.id_reflink AND
			ll1.id_member = ls1.id_member AND
			ls1.id_referer = ll2.id_reflink AND
			ll2.id_member = ls2.id_member
		;
		UPDATE
			smf_vrcs_stat ls0,
			smf_vrcs_link ll1,
			smf_vrcs_stat ls1,
			smf_vrcs_link ll2,
			smf_vrcs_stat ls2,
			smf_vrcs_link ll3,
			smf_vrcs_stat ls3
		SET
			ls3.posts_l3 = ls3.posts_l3 + NEW.posts - OLD.posts
		WHERE
			OLD.id_member = ls0.id_member AND
			ls0.id_referer = ll1.id_reflink AND
			ll1.id_member = ls1.id_member AND
			ls1.id_referer = ll2.id_reflink AND
			ll2.id_member = ls2.id_member AND
			ls2.id_referer = ll3.id_reflink AND
			ll3.id_member = ls3.id_member
		;
	END IF;
END;

DROP PROCEDURE IF EXISTS smf_vrcs_addmembers;
CREATE PROCEDURE smf_vrcs_addmembers (IN p_id_member MEDIUMINT UNSIGNED, IN p_id_referer INT UNSIGNED)
BEGIN
	IF p_id_referer NOT IN (SELECT id_reflink FROM smf_vrcs_link) THEN
		SET p_id_referer = 0;
	END IF;
	INSERT INTO smf_vrcs_stat (id_member, id_referer) VALUES (p_id_member, p_id_referer);
	IF p_id_referer != 0 THEN
		UPDATE smf_vrcs_link SET counter_l1 = counter_l1 + 1 WHERE id_reflink = p_id_referer;
		UPDATE
			smf_vrcs_link ll1,
			smf_vrcs_stat ls1,
			smf_vrcs_link ll2,
			smf_vrcs_stat ls2
		SET
			ls2.counter_l2 = ls2.counter_l2 + 1
		WHERE
			p_id_referer = ll1.id_reflink AND
			ll1.id_member = ls1.id_member AND
			ls1.id_referer = ll2.id_reflink AND
			ll2.id_member = ls2.id_member
		;
		UPDATE
			smf_vrcs_link ll1,
			smf_vrcs_stat ls1,
			smf_vrcs_link ll2,
			smf_vrcs_stat ls2,
			smf_vrcs_link ll3,
			smf_vrcs_stat ls3
		SET
			ls3.counter_l3 = ls3.counter_l3 + 1
		WHERE
			p_id_referer = ll1.id_reflink AND
			ll1.id_member = ls1.id_member AND
			ls1.id_referer = ll2.id_reflink AND
			ll2.id_member = ls2.id_member AND
			ls2.id_referer = ll3.id_reflink AND
			ll3.id_member = ls3.id_member
		;
	END IF;
END;

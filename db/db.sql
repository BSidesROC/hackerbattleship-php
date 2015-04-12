--
-- PostgreSQL database dump
--

DROP TABLE IF EXISTS bonus;
DROP TABLE IF EXISTS chal;
DROP TABLE IF EXISTS grid
DROP TABLE IF EXISTS reg;
DROP TABLE IF EXISTS score;
DROP TABLE IF EXISTS ships;

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: display_status; Type: TYPE; Schema: public; Owner: ctf
--

DROP TYPE IF EXISTS display_status;
CREATE TYPE display_status AS ENUM (
    'hit',
    'miss',
    'open'
);


ALTER TYPE public.display_status OWNER TO ctf;

--
-- Name: is_sunk(integer); Type: FUNCTION; Schema: public; Owner: ctf
--

DROP FUNCTION IF EXISTS is_sunk;
CREATE FUNCTION is_sunk(ship integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$
DECLARE
  spaces RECORD;
  hits RECORD;
BEGIN
  SELECT CAST(ship_grid_space AS bigint) INTO spaces FROM ships WHERE ship_id = $1;
  SELECT COUNT(status) INTO hits FROM grid WHERE ship_id = $1 AND status = 'hit';
  IF spaces = hits THEN
    RETURN TRUE;
  ELSE
    RETURN FALSE;
  END IF;
END
$_$;


ALTER FUNCTION public.is_sunk(ship integer) OWNER TO ctf;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: bonus; Type: TABLE; Schema: public; Owner: ctf; Tablespace: 
--

DROP TABLE IF EXISTS bonus;
CREATE TABLE bonus (
    bonus_id integer NOT NULL,
    bonus_key character varying(255) NOT NULL,
    bonus_value smallint DEFAULT 5 NOT NULL,
    bonus_descr character varying(255),
    bonus_open boolean DEFAULT true NOT NULL
);


ALTER TABLE public.bonus OWNER TO ctf;

--
-- Name: bonus_bonus_id_seq; Type: SEQUENCE; Schema: public; Owner: ctf
--

CREATE SEQUENCE bonus_bonus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.bonus_bonus_id_seq OWNER TO ctf;

--
-- Name: bonus_bonus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ctf
--

ALTER SEQUENCE bonus_bonus_id_seq OWNED BY bonus.bonus_id;


--
-- Name: chal; Type: TABLE; Schema: public; Owner: ctf; Tablespace: 
--

DROP TABLE IF EXISTS chal;
CREATE TABLE chal (
    chal_id integer NOT NULL,
    chal_key character varying(255) NOT NULL,
    chal_value smallint DEFAULT 10 NOT NULL,
    chal_descr character varying(255),
    chal_open boolean DEFAULT true NOT NULL
);


ALTER TABLE public.chal OWNER TO ctf;

--
-- Name: chal_chal_id_seq; Type: SEQUENCE; Schema: public; Owner: ctf
--

CREATE SEQUENCE chal_chal_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.chal_chal_id_seq OWNER TO ctf;

--
-- Name: chal_chal_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ctf
--

ALTER SEQUENCE chal_chal_id_seq OWNED BY chal.chal_id;


--
-- Name: grid; Type: TABLE; Schema: public; Owner: ctf; Tablespace: 
--

DROP TABLE IF EXISTS grid;
CREATE TABLE grid (
    col_id character(1) NOT NULL,
    row_id smallint NOT NULL,
    status display_status,
    ship_id smallint,
    chal_id smallint
);


ALTER TABLE public.grid OWNER TO ctf;

--
-- Name: reg; Type: TABLE; Schema: public; Owner: ctf; Tablespace: 
--

DROP TABLE IF EXISTS reg;
CREATE TABLE reg (
    team_id integer NOT NULL,
    team_name character varying(50) NOT NULL,
    team_email character varying(100) NOT NULL,
    team_hash character varying(255) NOT NULL,
    team_na character varying(255) NOT NULL
);


ALTER TABLE public.reg OWNER TO ctf;

--
-- Name: reg_team_id_seq; Type: SEQUENCE; Schema: public; Owner: ctf
--

CREATE SEQUENCE reg_team_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.reg_team_id_seq OWNER TO ctf;

--
-- Name: reg_team_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ctf
--

ALTER SEQUENCE reg_team_id_seq OWNED BY reg.team_id;


--
-- Name: score; Type: TABLE; Schema: public; Owner: ctf; Tablespace: 
--

DROP TABLE IF EXISTS score;
CREATE TABLE score (
    score_team smallint NOT NULL,
    score_tally smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.score OWNER TO ctf;

--
-- Name: ships; Type: TABLE; Schema: public; Owner: ctf; Tablespace: 
--

DROP TABLE IF EXISTS ships;
CREATE TABLE ships (
    ship_id integer NOT NULL,
    ship_nm character(20) NOT NULL,
    ship_grid_space smallint NOT NULL
);


ALTER TABLE public.ships OWNER TO ctf;

--
-- Name: ships_ship_id_seq; Type: SEQUENCE; Schema: public; Owner: ctf
--

CREATE SEQUENCE ships_ship_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ships_ship_id_seq OWNER TO ctf;

--
-- Name: ships_ship_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ctf
--

ALTER SEQUENCE ships_ship_id_seq OWNED BY ships.ship_id;


--
-- Name: bonus_id; Type: DEFAULT; Schema: public; Owner: ctf
--

ALTER TABLE ONLY bonus ALTER COLUMN bonus_id SET DEFAULT nextval('bonus_bonus_id_seq'::regclass);


--
-- Name: chal_id; Type: DEFAULT; Schema: public; Owner: ctf
--

ALTER TABLE ONLY chal ALTER COLUMN chal_id SET DEFAULT nextval('chal_chal_id_seq'::regclass);


--
-- Name: team_id; Type: DEFAULT; Schema: public; Owner: ctf
--

ALTER TABLE ONLY reg ALTER COLUMN team_id SET DEFAULT nextval('reg_team_id_seq'::regclass);


--
-- Name: ship_id; Type: DEFAULT; Schema: public; Owner: ctf
--

ALTER TABLE ONLY ships ALTER COLUMN ship_id SET DEFAULT nextval('ships_ship_id_seq'::regclass);


--
-- Data for Name: bonus; Type: TABLE DATA; Schema: public; Owner: ctf
--

COPY bonus (bonus_id, bonus_key, bonus_value, bonus_descr, bonus_open) FROM stdin;
1	T01HISBZb3UgZm91bmQgdGhlIGdpdGh1YiE=	5	this is an example\nbonus challenge	t
\.


--
-- Name: bonus_bonus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ctf
--

SELECT pg_catalog.setval('bonus_bonus_id_seq', 1, true);


--
-- Data for Name: chal; Type: TABLE DATA; Schema: public; Owner: ctf
--

COPY chal (chal_id, chal_key, chal_value, chal_descr, chal_open) FROM stdin;
1	cmVhZGluZyBpcyBGVU5kYW1lbnRhbA==	10	this is a demo challenge. it is\nassociated wtih grid space A1. The answer is 'reading is FUNdamental'	t
2	cmVhZGluZyBpcyBGVU5kYW1lbnRhbA==	10	this is a demo challenge. it is\nassociated wtih grid space A2. The answer is 'reading is FUNdamental'	t
\.


--
-- Name: chal_chal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ctf
--

SELECT pg_catalog.setval('chal_chal_id_seq', 2, true);


--
-- Data for Name: grid; Type: TABLE DATA; Schema: public; Owner: ctf
--

COPY grid (col_id, row_id, status, ship_id, chal_id) FROM stdin;
A	1	open	1	1
A	2	open	0	2
A	3	open	0	3
A	4	open	0	4
A	5	open	0	5
A	6	open	0	6
A	7	open	4	7
B	1	open	1	8
B	2	open	0	9
B	3	open	0	10
B	4	open	0	11
B	5	open	0	12
B	6	open	0	13
B	7	open	4	14
C	1	open	1	15
C	2	open	0	16
C	3	open	3	17
C	4	open	3	18
C	5	open	3	19
C	6	open	0	20
C	7	open	4	21
D	1	open	1	22
D	2	open	0	23
D	3	open	0	24
D	4	open	0	25
D	5	open	0	26
D	6	open	0	27
D	7	open	0	28
E	1	open	1	29
E	2	open	0	30
E	3	open	0	31
E	4	open	2	32
E	5	open	2	33
E	6	open	2	34
E	7	open	2	35
F	1	open	0	36
F	2	open	0	37
F	3	open	0	38
F	4	open	0	39
F	5	open	5	40
F	6	open	0	41
F	7	open	0	42
G	1	open	0	43
G	2	open	0	44
G	3	open	0	45
G	4	open	0	46
G	5	open	5	47
G	6	open	0	48
G	7	open	0	49
\.


--
-- Data for Name: reg; Type: TABLE DATA; Schema: public; Owner: ctf
--

COPY reg (team_id, team_name, team_email, team_hash, team_na) FROM stdin;
\.


--
-- Name: reg_team_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ctf
--

SELECT pg_catalog.setval('reg_team_id_seq', 1, false);


--
-- Data for Name: score; Type: TABLE DATA; Schema: public; Owner: ctf
--

COPY score (score_team, score_tally) FROM stdin;
\.


--
-- Data for Name: ships; Type: TABLE DATA; Schema: public; Owner: ctf
--

COPY ships (ship_id, ship_nm, ship_grid_space) FROM stdin;
1	aircraft carrier    	5
2	battleship          	4
3	submarine           	3
4	destroyer           	3
5	patrol boat         	2
\.


--
-- Name: ships_ship_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ctf
--

SELECT pg_catalog.setval('ships_ship_id_seq', 1, false);


--
-- Name: grid_pkey; Type: CONSTRAINT; Schema: public; Owner: ctf; Tablespace: 
--

ALTER TABLE ONLY grid
    ADD CONSTRAINT grid_pkey PRIMARY KEY (col_id, row_id);


--
-- Name: reg_team_name_key; Type: CONSTRAINT; Schema: public; Owner: ctf; Tablespace: 
--

ALTER TABLE ONLY reg
    ADD CONSTRAINT reg_team_name_key UNIQUE (team_name);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: bonus; Type: ACL; Schema: public; Owner: ctf
--

REVOKE ALL ON TABLE bonus FROM PUBLIC;
REVOKE ALL ON TABLE bonus FROM ctf;
GRANT ALL ON TABLE bonus TO ctf;
GRANT SELECT ON TABLE bonus TO ctf_ro;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE bonus TO ctf_rw;


--
-- Name: bonus_bonus_id_seq; Type: ACL; Schema: public; Owner: ctf
--

REVOKE ALL ON SEQUENCE bonus_bonus_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE bonus_bonus_id_seq FROM ctf;
GRANT ALL ON SEQUENCE bonus_bonus_id_seq TO ctf;
GRANT SELECT ON SEQUENCE bonus_bonus_id_seq TO ctf_ro;
GRANT SELECT,UPDATE ON SEQUENCE bonus_bonus_id_seq TO ctf_rw;


--
-- Name: chal; Type: ACL; Schema: public; Owner: ctf
--

REVOKE ALL ON TABLE chal FROM PUBLIC;
REVOKE ALL ON TABLE chal FROM ctf;
GRANT ALL ON TABLE chal TO ctf;
GRANT SELECT ON TABLE chal TO ctf_ro;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE chal TO ctf_rw;


--
-- Name: chal_chal_id_seq; Type: ACL; Schema: public; Owner: ctf
--

REVOKE ALL ON SEQUENCE chal_chal_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE chal_chal_id_seq FROM ctf;
GRANT ALL ON SEQUENCE chal_chal_id_seq TO ctf;
GRANT SELECT ON SEQUENCE chal_chal_id_seq TO ctf_ro;
GRANT SELECT,UPDATE ON SEQUENCE chal_chal_id_seq TO ctf_rw;


--
-- Name: grid; Type: ACL; Schema: public; Owner: ctf
--

REVOKE ALL ON TABLE grid FROM PUBLIC;
REVOKE ALL ON TABLE grid FROM ctf;
GRANT ALL ON TABLE grid TO ctf;
GRANT SELECT ON TABLE grid TO ctf_ro;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE grid TO ctf_rw;


--
-- Name: reg; Type: ACL; Schema: public; Owner: ctf
--

REVOKE ALL ON TABLE reg FROM PUBLIC;
REVOKE ALL ON TABLE reg FROM ctf;
GRANT ALL ON TABLE reg TO ctf;
GRANT SELECT ON TABLE reg TO ctf_ro;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE reg TO ctf_rw;


--
-- Name: reg_team_id_seq; Type: ACL; Schema: public; Owner: ctf
--

REVOKE ALL ON SEQUENCE reg_team_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE reg_team_id_seq FROM ctf;
GRANT ALL ON SEQUENCE reg_team_id_seq TO ctf;
GRANT SELECT ON SEQUENCE reg_team_id_seq TO ctf_ro;
GRANT SELECT,UPDATE ON SEQUENCE reg_team_id_seq TO ctf_rw;


--
-- Name: score; Type: ACL; Schema: public; Owner: ctf
--

REVOKE ALL ON TABLE score FROM PUBLIC;
REVOKE ALL ON TABLE score FROM ctf;
GRANT ALL ON TABLE score TO ctf;
GRANT SELECT ON TABLE score TO ctf_ro;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE score TO ctf_rw;


--
-- Name: ships; Type: ACL; Schema: public; Owner: ctf
--

REVOKE ALL ON TABLE ships FROM PUBLIC;
REVOKE ALL ON TABLE ships FROM ctf;
GRANT ALL ON TABLE ships TO ctf;
GRANT SELECT ON TABLE ships TO ctf_ro;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE ships TO ctf_rw;


--
-- Name: ships_ship_id_seq; Type: ACL; Schema: public; Owner: ctf
--

REVOKE ALL ON SEQUENCE ships_ship_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE ships_ship_id_seq FROM ctf;
GRANT ALL ON SEQUENCE ships_ship_id_seq TO ctf;
GRANT SELECT ON SEQUENCE ships_ship_id_seq TO ctf_ro;
GRANT SELECT,UPDATE ON SEQUENCE ships_ship_id_seq TO ctf_rw;

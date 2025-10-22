PGDMP\00\00;\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00	\00\00\00\00}\00\00\00\00\00\00\00\00\00\00\00\00myapp\00 \00\00\0015.13 (Debian 15.13-1.pgdg120+1)\00 \00\00\0016.4 (Ubuntu 16.4-1.pgdg22.04+1)\00\82\00\00\00\00\F7
\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00ENCODING\00\00\00\00ENCODING\00\00\00\00\00\00\00\00SET client_encoding = 'UTF8';
\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\F8
\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00
\00\00\00STDSTRINGS\00
\00\00\00STDSTRINGS\00\00\00\00\00(\00\00\00SET standard_conforming_strings = 'on';
\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\F9
\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00
\00\00\00SEARCHPATH\00
\00\00\00SEARCHPATH\00\00\00\00\008\00\00\00SELECT pg_catalog.set_config('search_path', '', false);
\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\FA
\00\00\00\00\00\00\00\00\00\00\001262\00\00\00\0016384\00\00\00\00myapp\00\00\00\00DATABASE\00\00\00\00\00p\00\00\00CREATE DATABASE myapp WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'en_US.utf8';
\00\00\00\00DROP DATABASE myapp;
\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00r\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0024670\00
\00\00\00admin_type\00\00\00\00TYPE\00\00\00\00\00O\00\00\00CREATE TYPE public.admin_type AS ENUM (
    'super',
    'data',
    'user'
);
\00\00\00\00DROP TYPE public.admin_type;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\9C\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0024954\00\00\00\00appointment_cancelled_by\00\00\00\00TYPE\00\00\00\00\00S\00\00\00CREATE TYPE public.appointment_cancelled_by AS ENUM (
    'parent',
    'staff'
);
\00+\00\00\00DROP TYPE public.appointment_cancelled_by;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\99\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0024948\00\00\00\00appointment_requested_by\00\00\00\00TYPE\00\00\00\00\00S\00\00\00CREATE TYPE public.appointment_requested_by AS ENUM (
    'staff',
    'parent'
);
\00+\00\00\00DROP TYPE public.appointment_requested_by;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\9F\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0024983\00\00\00\00appointment_status\00\00\00\00TYPE\00\00\00\00\00\AA\00\00\00CREATE TYPE public.appointment_status AS ENUM (
    'pending',
    'confirmed',
    'cancelled',
    'cancel_requested',
    'reschedule_requested',
    'rescheduled'
);
\00%\00\00\00DROP TYPE public.appointment_status;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\93\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0024910\00\00\00\00child_gender\00\00\00\00TYPE\00\00\00\00\00F\00\00\00CREATE TYPE public.child_gender AS ENUM (
    'male',
    'female'
);
\00\00\00\00DROP TYPE public.child_gender;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\90\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0024904\00\00\00\00child_status\00\00\00\00TYPE\00\00\00\00\00H\00\00\00CREATE TYPE public.child_status AS ENUM (
    'good',
    'critical'
);
\00\00\00\00DROP TYPE public.child_status;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\AB\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0025270\00\00\00\00maternal_health_status_enum\00\00\00\00TYPE\00\00\00\00\00n\00\00\00CREATE TYPE public.maternal_health_status_enum AS ENUM (
    'good',
    'critical',
    'needs_attention'
);
\00.\00\00\00DROP TYPE public.maternal_health_status_enum;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\B1\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0025284\00\00\00\00maternal_stage_enum\00\00\00\00TYPE\00\00\00\00\00y\00\00\00CREATE TYPE public.maternal_stage_enum AS ENUM (
    'first_trimester',
    'second_trimester',
    'third_trimester'
);
\00&\00\00\00DROP TYPE public.maternal_stage_enum;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\B7\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0025325\00\00\00\00maternal_status\00\00\00\00TYPE\00\00\00\00\00F\00\00\00CREATE TYPE public.maternal_status AS ENUM (
    'good',
    'bad'
);
\00"\00\00\00DROP TYPE public.maternal_status;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\AE\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0025278\00\00\00\00maternal_type_enum\00\00\00\00TYPE\00\00\00\00\00T\00\00\00CREATE TYPE public.maternal_type_enum AS ENUM (
    'antenatal',
    'postnatal'
);
\00%\00\00\00DROP TYPE public.maternal_type_enum;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00`\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0024586\00\00\00\00parent_type\00\00\00\00TYPE\00\00\00\00\00W\00\00\00CREATE TYPE public.parent_type AS ENUM (
    'mother',
    'father',
    'guardian'
);
\00\00\00\00DROP TYPE public.parent_type;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\87\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0024874\00\00\00\00patient_type\00\00\00\00TYPE\00\00\00\00\00I\00\00\00CREATE TYPE public.patient_type AS ENUM (
    'maternal',
    'child'
);
\00\00\00\00DROP TYPE public.patient_type;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00]\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0024577\00	\00\00\00user_role\00\00\00\00TYPE\00\00\00\00\00]\00\00\00CREATE TYPE public.user_role AS ENUM (
    'parent',
    'doctor',
    'phm',
    'admin'
);
\00\00\00\00DROP TYPE public.user_role;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\A8\00\00\00\00\00\00\00\00\00\00\001247\00\00\00\0025036\00\00\00\00vaccination_status_enum\00\00\00\00TYPE\00\00\00\00\00f\00\00\00CREATE TYPE public.vaccination_status_enum AS ENUM (
    'pending',
    'completed',
    'overdue'
);
\00*\00\00\00DROP TYPE public.vaccination_status_enum;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\E2\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024724\00\00\00\00admin_permissions\00\00\00\00TABLE\00\00\00\00\00\DE\00\00\00CREATE TABLE public.admin_permissions (
    admin_id integer NOT NULL,
    permission_id integer NOT NULL,
    active boolean DEFAULT false,
    granted_by integer,
    granted_at timestamp with time zone DEFAULT now()
);
\00%\00\00\00DROP TABLE public.admin_permissions;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\E1\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024708\00\00\00\00admin_type_permissions\00\00\00\00TABLE\00\00\00\00\00\99\00\00\00CREATE TABLE public.admin_type_permissions (
    admin_type_id integer NOT NULL,
    permission_id integer NOT NULL,
    status boolean DEFAULT false
);
\00*\00\00\00DROP TABLE public.admin_type_permissions;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\DD\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024678\00\00\00\00admin_types\00\00\00\00TABLE\00\00\00\00\00b\00\00\00CREATE TABLE public.admin_types (
    id integer NOT NULL,
    type public.admin_type NOT NULL
);
\00\00\00\00DROP TABLE public.admin_types;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00882\00\00\00\00\00\00\00\00\00\00\00\00\DC\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024677\00\00\00\00admin_types_id_seq\00\00\00\00SEQUENCE\00\00\00\00\00\8A\00\00\00CREATE SEQUENCE public.admin_types_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
\00)\00\00\00DROP SEQUENCE public.admin_types_id_seq;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00221\00\00\00\00\00\00\00\00\00\00\00\00\FB
\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00admin_types_id_seq\00\00\00\00SEQUENCE OWNED BY\00\00\00\00\00I\00\00\00ALTER SEQUENCE public.admin_types_id_seq OWNED BY public.admin_types.id;
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00220\00\00\00\00\00\00\00\00\00\00\00\00\DE\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024684\00\00\00\00admins\00\00\00\00TABLE\00\00\00\00\00S\00\00\00CREATE TABLE public.admins (
    id integer NOT NULL,
    admin_type_id integer
);
\00\00\00\00DROP TABLE public.admins;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\EA\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024996\00\00\00\00appointments\00\00\00\00TABLE\00\00\00\00\00\AA\00\00CREATE TABLE public.appointments (
    id integer NOT NULL,
    patient_id integer,
    requested_by public.appointment_requested_by NOT NULL,
    datetime timestamp without time zone NOT NULL,
    location character varying(255) DEFAULT 'Not Allocated'::character varying,
    status public.appointment_status DEFAULT 'pending'::public.appointment_status,
    purpose character varying(255),
    notes json,
    reschedule_reason character varying(255),
    cancel_reason character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    phm_id integer,
    doctor_id integer
);
\00 \00\00\00DROP TABLE public.appointments;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00927\00\00\00\00921\00\00\00\00927\00\00\00\00\00\00\00\00\00\00\00\00\E9\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024995\00\00\00\00appointments_id_seq\00\00\00\00SEQUENCE\00\00\00\00\00\8B\00\00\00CREATE SEQUENCE public.appointments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
\00*\00\00\00DROP SEQUENCE public.appointments_id_seq;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00234\00\00\00\00\00\00\00\00\00\00\00\00\FC
\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00appointments_id_seq\00\00\00\00SEQUENCE OWNED BY\00\00\00\00\00K\00\00\00ALTER SEQUENCE public.appointments_id_seq OWNED BY public.appointments.id;
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00233\00\00\00\00\00\00\00\00\00\00\00\00\E4\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024747\00\00\00\00areas\00\00\00\00TABLE\00\00\00\00\00O\00\00\00CREATE TABLE public.areas (
    id integer NOT NULL,
    code text NOT NULL
);
\00\00\00\00DROP TABLE public.areas;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\E3\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024746\00\00\00\00areas_id_seq\00\00\00\00SEQUENCE\00\00\00\00\00\84\00\00\00CREATE SEQUENCE public.areas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
\00#\00\00\00DROP SEQUENCE public.areas_id_seq;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00228\00\00\00\00\00\00\00\00\00\00\00\00\FD
\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00areas_id_seq\00\00\00\00SEQUENCE OWNED BY\00\00\00\00\00=\00\00\00ALTER SEQUENCE public.areas_id_seq OWNED BY public.areas.id;
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00227\00\00\00\00\00\00\00\00\00\00\00\00\EC\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0025020\00\00\00\00child_stats\00\00\00\00TABLE\00\00\00\00\00\AF\00\00CREATE TABLE public.child_stats (
    id integer NOT NULL,
    child_id integer,
    visit_date date NOT NULL,
    age_recorded_at character varying(20),
    height numeric(5,2),
    weight numeric(5,2),
    head_circum numeric(5,2),
    risk_flags character varying(255),
    notes json,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
\00\00\00\00DROP TABLE public.child_stats;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\EB\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0025019\00\00\00\00child_stats_id_seq\00\00\00\00SEQUENCE\00\00\00\00\00\8A\00\00\00CREATE SEQUENCE public.child_stats_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
\00)\00\00\00DROP SEQUENCE public.child_stats_id_seq;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00236\00\00\00\00\00\00\00\00\00\00\00\00\FE
\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00child_stats_id_seq\00\00\00\00SEQUENCE OWNED BY\00\00\00\00\00I\00\00\00ALTER SEQUENCE public.child_stats_id_seq OWNED BY public.child_stats.id;
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00235\00\00\00\00\00\00\00\00\00\00\00\00\E8\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024915\00\00\00\00children\00\00\00\00TABLE\00\00\00\00\00\C6\00\00CREATE TABLE public.children (
    id integer NOT NULL,
    parent_id integer,
    phm_id integer,
    name character varying(50) NOT NULL,
    date_of_birth date NOT NULL,
    gender public.child_gender NOT NULL,
    health_status public.child_status,
    gs_division character varying(50) DEFAULT ''::character varying NOT NULL,
    notes json,
    vaccination_status public.vaccination_status_enum DEFAULT 'pending'::public.vaccination_status_enum
);
\00\00\00\00DROP TABLE public.children;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00936\00\00\00\00912\00\00\00\00915\00\00\00\00936\00\00\00\00\00\00\00\00\00\00\00\00\DA\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024644\00\00\00\00doctors\00\00\00\00TABLE\00\00\00\00\009\00\00\00CREATE TABLE public.doctors (
    id integer NOT NULL
);
\00\00\00\00DROP TABLE public.doctors;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\E7\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024888\00\00\00\00maternal\00\00\00\00TABLE\00\00\00\00\00\B0\00\00CREATE TABLE public.maternal (
    id integer NOT NULL,
    parent_id integer,
    pregnancy_date date,
    additional_info json,
    type public.maternal_type_enum DEFAULT 'antenatal'::public.maternal_type_enum,
    stage public.maternal_stage_enum DEFAULT 'first_trimester'::public.maternal_stage_enum,
    health_status public.maternal_health_status_enum DEFAULT 'good'::public.maternal_health_status_enum,
    phm_id integer
);
\00\00\00\00DROP TABLE public.maternal;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00942\00\00\00\00945\00\00\00\00939\00\00\00\00945\00\00\00\00939\00\00\00\00942\00\00\00\00\00\00\00\00\00\00\00\00\EE\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0025310\00\00\00\00maternal_stats\00\00\00\00TABLE\00\00\00\00\00\F5\00\00CREATE TABLE public.maternal_stats (
    id integer NOT NULL,
    maternal_id integer,
    visit_date date NOT NULL,
    trimester public.maternal_stage_enum NOT NULL,
    weight numeric(5,2),
    height numeric(5,2),
    bmi numeric(5,2),
    blood_pressure character varying(10),
    blood_sugar numeric(5,2),
    fundal_height numeric(5,2),
    notes text,
    created_at timestamp without time zone DEFAULT now(),
    health_status public.maternal_status DEFAULT 'good'::public.maternal_status
);
\00"\00\00\00DROP TABLE public.maternal_stats;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00951\00\00\00\00945\00\00\00\00951\00\00\00\00\00\00\00\00\00\00\00\00\ED\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0025309\00\00\00\00maternal_stats_id_seq\00\00\00\00SEQUENCE\00\00\00\00\00\8D\00\00\00CREATE SEQUENCE public.maternal_stats_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
\00,\00\00\00DROP SEQUENCE public.maternal_stats_id_seq;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00238\00\00\00\00\00\00\00\00\00\00\00\00\FF
\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00maternal_stats_id_seq\00\00\00\00SEQUENCE OWNED BY\00\00\00\00\00O\00\00\00ALTER SEQUENCE public.maternal_stats_id_seq OWNED BY public.maternal_stats.id;
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00237\00\00\00\00\00\00\00\00\00\00\00\00\D8\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024614\00\00\00\00parents\00\00\00\00TABLE\00\00\00\00\00\DF\00\00\00CREATE TABLE public.parents (
    id integer NOT NULL,
    type public.parent_type NOT NULL,
    nic text NOT NULL,
    address text NOT NULL,
    area_id integer,
    gs_division character varying(100),
    age integer
);
\00\00\00\00DROP TABLE public.parents;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00864\00\00\00\00\00\00\00\00\00\00\00\00\E6\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024880\00\00\00\00patients\00\00\00\00TABLE\00\00\00\00\00\ED\00\00\00CREATE TABLE public.patients (
    id integer NOT NULL,
    type public.patient_type NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
\00\00\00\00DROP TABLE public.patients;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00903\00\00\00\00\00\00\00\00\00\00\00\00\E5\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024879\00\00\00\00patients_id_seq\00\00\00\00SEQUENCE\00\00\00\00\00\87\00\00\00CREATE SEQUENCE public.patients_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
\00&\00\00\00DROP SEQUENCE public.patients_id_seq;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00230\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00patients_id_seq\00\00\00\00SEQUENCE OWNED BY\00\00\00\00\00C\00\00\00ALTER SEQUENCE public.patients_id_seq OWNED BY public.patients.id;
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00229\00\00\00\00\00\00\00\00\00\00\00\00\E0\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024700\00\00\00\00permissions\00\00\00\00TABLE\00\00\00\00\00L\00\00\00CREATE TABLE public.permissions (
    id integer NOT NULL,
    type text
);
\00\00\00\00DROP TABLE public.permissions;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\DF\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024699\00\00\00\00permissions_id_seq\00\00\00\00SEQUENCE\00\00\00\00\00\8A\00\00\00CREATE SEQUENCE public.permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
\00)\00\00\00DROP SEQUENCE public.permissions_id_seq;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00224\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00permissions_id_seq\00\00\00\00SEQUENCE OWNED BY\00\00\00\00\00I\00\00\00ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00223\00\00\00\00\00\00\00\00\00\00\00\00\DB\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024654\00\00\00\00public_health_midwives\00\00\00\00TABLE\00\00\00\00\00x\00\00\00CREATE TABLE public.public_health_midwives (
    id integer NOT NULL,
    supervisor_id integer,
    area_id integer
);
\00*\00\00\00DROP TABLE public.public_health_midwives;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\D9\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024628\00\00\00\00staffs\00\00\00\00TABLE\00\00\00\00\00d\00\00\00CREATE TABLE public.staffs (
    id integer NOT NULL,
    nic text NOT NULL,
    license_no text
);
\00\00\00\00DROP TABLE public.staffs;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00\00\00\00\00\00\00\00\00\D7\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024603\00\00\00\00users\00\00\00\00TABLE\00\00\00\00\00\E4\00\00\00CREATE TABLE public.users (
    id integer NOT NULL,
    name text NOT NULL,
    email text NOT NULL,
    password_hash text NOT NULL,
    role public.user_role NOT NULL,
    created_at timestamp with time zone DEFAULT now()
);
\00\00\00\00DROP TABLE public.users;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00heap\00\00\00\00postgres\00\00\00\00false\00\00\00\00861\00\00\00\00\00\00\00\00\00\00\00\00\D6\00\00\00\00\00\00\00\00\00\00\00\001259\00\00\00\0024602\00\00\00\00users_id_seq\00\00\00\00SEQUENCE\00\00\00\00\00\84\00\00\00CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
\00#\00\00\00DROP SEQUENCE public.users_id_seq;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00215\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00users_id_seq\00\00\00\00SEQUENCE OWNED BY\00\00\00\00\00=\00\00\00ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00214\00\00\00\00\00\00\00\00\00\00\00\00\F2\00\00\00\00\00\00\00\00\00\00\002604\00\00\00\0024681\00\00\00\00admin_types id\00\00\00\00DEFAULT\00\00\00\00\00p\00\00\00ALTER TABLE ONLY public.admin_types ALTER COLUMN id SET DEFAULT nextval('public.admin_types_id_seq'::regclass);
\00=\00\00\00ALTER TABLE public.admin_types ALTER COLUMN id DROP DEFAULT;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00220\00\00\00\00221\00\00\00\00221\00\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002604\00\00\00\0024999\00\00\00\00appointments id\00\00\00\00DEFAULT\00\00\00\00\00r\00\00\00ALTER TABLE ONLY public.appointments ALTER COLUMN id SET DEFAULT nextval('public.appointments_id_seq'::regclass);
\00>\00\00\00ALTER TABLE public.appointments ALTER COLUMN id DROP DEFAULT;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00234\00\00\00\00233\00\00\00\00234\00\00\00\00\00\00\00\00\00\00\00\00\F7\00\00\00\00\00\00\00\00\00\00\002604\00\00\00\0024750\00\00\00\00areas id\00\00\00\00DEFAULT\00\00\00\00\00d\00\00\00ALTER TABLE ONLY public.areas ALTER COLUMN id SET DEFAULT nextval('public.areas_id_seq'::regclass);
\007\00\00\00ALTER TABLE public.areas ALTER COLUMN id DROP DEFAULT;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00227\00\00\00\00228\00\00\00\00228\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002604\00\00\00\0025023\00\00\00\00child_stats id\00\00\00\00DEFAULT\00\00\00\00\00p\00\00\00ALTER TABLE ONLY public.child_stats ALTER COLUMN id SET DEFAULT nextval('public.child_stats_id_seq'::regclass);
\00=\00\00\00ALTER TABLE public.child_stats ALTER COLUMN id DROP DEFAULT;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00236\00\00\00\00235\00\00\00\00236\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002604\00\00\00\0025313\00\00\00\00maternal_stats id\00\00\00\00DEFAULT\00\00\00\00\00v\00\00\00ALTER TABLE ONLY public.maternal_stats ALTER COLUMN id SET DEFAULT nextval('public.maternal_stats_id_seq'::regclass);
\00@\00\00\00ALTER TABLE public.maternal_stats ALTER COLUMN id DROP DEFAULT;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00238\00\00\00\00237\00\00\00\00238\00\00\00\00\00\00\00\00\00\00\00\00\F8\00\00\00\00\00\00\00\00\00\00\002604\00\00\00\0024883\00\00\00\00patients id\00\00\00\00DEFAULT\00\00\00\00\00j\00\00\00ALTER TABLE ONLY public.patients ALTER COLUMN id SET DEFAULT nextval('public.patients_id_seq'::regclass);
\00:\00\00\00ALTER TABLE public.patients ALTER COLUMN id DROP DEFAULT;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00230\00\00\00\00229\00\00\00\00230\00\00\00\00\00\00\00\00\00\00\00\00\F3\00\00\00\00\00\00\00\00\00\00\002604\00\00\00\0024703\00\00\00\00permissions id\00\00\00\00DEFAULT\00\00\00\00\00p\00\00\00ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);
\00=\00\00\00ALTER TABLE public.permissions ALTER COLUMN id DROP DEFAULT;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00224\00\00\00\00223\00\00\00\00224\00\00\00\00\00\00\00\00\00\00\00\00\F0\00\00\00\00\00\00\00\00\00\00\002604\00\00\00\0024606\00\00\00\00users id\00\00\00\00DEFAULT\00\00\00\00\00d\00\00\00ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
\007\00\00\00ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00215\00\00\00\00214\00\00\00\00215\00\00\00\00\00\00\00\00\00\00\00\00\E8
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024724\00\00\00\00admin_permissions\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00226\00\00\00\AF\9C\00\00\00\00\00\00\00\E7
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024708\00\00\00\00admin_type_permissions\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00225\00\00\00ɜ\00\00\00\00\00\00\00\E3
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024678\00\00\00\00admin_types\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00221\00\00\00\E3\9C\00\00\00\00\00\00\00\E4
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024684\00\00\00\00admins\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00222\00\00\00E\9D\00\00\00\00\00\00\00\F0
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024996\00\00\00\00appointments\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00234\00\00\00\88\9D\00\00\00\00\00\00\00\EA
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024747\00\00\00\00areas\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00228\00\00\00\AF\9E\00\00\00\00\00\00\00\F2
\00\00\00\00\00\00\00\00\00\000\00\00\00\0025020\00\00\00\00child_stats\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00236\00\00\00_\9F\00\00\00\00\00\00\00\EE
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024915\00\00\00\00children\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00232\00\00\00y\9F\00\00\00\00\00\00\00\E0
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024644\00\00\00\00doctors\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00218\00\00\008\A0\00\00\00\00\00\00\00\ED
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024888\00\00\00\00maternal\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00231\00\00\00y\A0\00\00\00\00\00\00\00\F4
\00\00\00\00\00\00\00\00\00\000\00\00\00\0025310\00\00\00\00maternal_stats\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00238\00\00\000\A1\00\00\00\00\00\00\00\DE
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024614\00\00\00\00parents\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00216\00\00\00S\A2\00\00\00\00\00\00\00\EC
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024880\00\00\00\00patients\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00230\00\00\00Ң\00\00\00\00\00\00\00\E6
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024700\00\00\00\00permissions\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00224\00\00\00\8B\A3\00\00\00\00\00\00\00\E1
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024654\00\00\00\00public_health_midwives\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00219\00\00\00\A5\A3\00\00\00\00\00\00\00\DF
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024628\00\00\00\00staffs\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00217\00\00\00\F9\A3\00\00\00\00\00\00\00\DD
\00\00\00\00\00\00\00\00\00\000\00\00\00\0024603\00\00\00\00users\00
\00\00\00TABLE DATA\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00215\00\00\00x\A4\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00admin_types_id_seq\00\00\00\00SEQUENCE SET\00\00\00\00\00@\00\00\00SELECT pg_catalog.setval('public.admin_types_id_seq', 3, true);
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00220\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00appointments_id_seq\00\00\00\00SEQUENCE SET\00\00\00\00\00B\00\00\00SELECT pg_catalog.setval('public.appointments_id_seq', 11, true);
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00233\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00areas_id_seq\00\00\00\00SEQUENCE SET\00\00\00\00\00;\00\00\00SELECT pg_catalog.setval('public.areas_id_seq', 10, true);
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00227\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00child_stats_id_seq\00\00\00\00SEQUENCE SET\00\00\00\00\00@\00\00\00SELECT pg_catalog.setval('public.child_stats_id_seq', 2, true);
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00235\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00maternal_stats_id_seq\00\00\00\00SEQUENCE SET\00\00\00\00\00C\00\00\00SELECT pg_catalog.setval('public.maternal_stats_id_seq', 7, true);
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00237\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00patients_id_seq\00\00\00\00SEQUENCE SET\00\00\00\00\00=\00\00\00SELECT pg_catalog.setval('public.patients_id_seq', 7, true);
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00229\00\00\00\00\00\00\00\00\00\00\00\00	\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00permissions_id_seq\00\00\00\00SEQUENCE SET\00\00\00\00\00A\00\00\00SELECT pg_catalog.setval('public.permissions_id_seq', 1, false);
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00223\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\000\00\00\00\000\00\00\00\00users_id_seq\00\00\00\00SEQUENCE SET\00\00\00\00\00:\00\00\00SELECT pg_catalog.setval('public.users_id_seq', 6, true);
\00\00\00\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00214\00\00\00\00\00\00\00\00\00\00\00\00&
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024730\00(\00\00\00admin_permissions admin_permissions_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00{\00\00\00ALTER TABLE ONLY public.admin_permissions
    ADD CONSTRAINT admin_permissions_pkey PRIMARY KEY (admin_id, permission_id);
\00R\00\00\00ALTER TABLE ONLY public.admin_permissions DROP CONSTRAINT admin_permissions_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00226\00\00\00\00226\00\00\00\00\00\00\00\00\00\00\00\00$
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024713\002\00\00\00admin_type_permissions admin_type_permissions_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00\8A\00\00\00ALTER TABLE ONLY public.admin_type_permissions
    ADD CONSTRAINT admin_type_permissions_pkey PRIMARY KEY (admin_type_id, permission_id);
\00\\00\00\00ALTER TABLE ONLY public.admin_type_permissions DROP CONSTRAINT admin_type_permissions_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00225\00\00\00\00225\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024683\00\00\00\00admin_types admin_types_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00Z\00\00\00ALTER TABLE ONLY public.admin_types
    ADD CONSTRAINT admin_types_pkey PRIMARY KEY (id);
\00F\00\00\00ALTER TABLE ONLY public.admin_types DROP CONSTRAINT admin_types_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00221\00\00\00\00\00\00\00\00\00\00\00\00 
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024688\00\00\00\00admins admins_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00P\00\00\00ALTER TABLE ONLY public.admins
    ADD CONSTRAINT admins_pkey PRIMARY KEY (id);
\00<\00\00\00ALTER TABLE ONLY public.admins DROP CONSTRAINT admins_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00222\00\00\00\00\00\00\00\00\00\00\00\000
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0025007\00\00\00\00appointments appointments_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00\\00\00\00ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_pkey PRIMARY KEY (id);
\00H\00\00\00ALTER TABLE ONLY public.appointments DROP CONSTRAINT appointments_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00234\00\00\00\00\00\00\00\00\00\00\00\00(
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024754\00\00\00\00areas areas_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00N\00\00\00ALTER TABLE ONLY public.areas
    ADD CONSTRAINT areas_pkey PRIMARY KEY (id);
\00:\00\00\00ALTER TABLE ONLY public.areas DROP CONSTRAINT areas_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00228\00\00\00\00\00\00\00\00\00\00\00\002
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0025029\00\00\00\00child_stats child_stats_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00Z\00\00\00ALTER TABLE ONLY public.child_stats
    ADD CONSTRAINT child_stats_pkey PRIMARY KEY (id);
\00F\00\00\00ALTER TABLE ONLY public.child_stats DROP CONSTRAINT child_stats_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00236\00\00\00\00\00\00\00\00\00\00\00\00.
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024919\00\00\00\00children children_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00T\00\00\00ALTER TABLE ONLY public.children
    ADD CONSTRAINT children_pkey PRIMARY KEY (id);
\00@\00\00\00ALTER TABLE ONLY public.children DROP CONSTRAINT children_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00232\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024648\00\00\00\00doctors doctors_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00R\00\00\00ALTER TABLE ONLY public.doctors
    ADD CONSTRAINT doctors_pkey PRIMARY KEY (id);
\00>\00\00\00ALTER TABLE ONLY public.doctors DROP CONSTRAINT doctors_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00218\00\00\00\00\00\00\00\00\00\00\00\00,
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024892\00\00\00\00maternal maternal_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00T\00\00\00ALTER TABLE ONLY public.maternal
    ADD CONSTRAINT maternal_pkey PRIMARY KEY (id);
\00@\00\00\00ALTER TABLE ONLY public.maternal DROP CONSTRAINT maternal_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00231\00\00\00\00\00\00\00\00\00\00\00\004
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0025318\00"\00\00\00maternal_stats maternal_stats_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00`\00\00\00ALTER TABLE ONLY public.maternal_stats
    ADD CONSTRAINT maternal_stats_pkey PRIMARY KEY (id);
\00L\00\00\00ALTER TABLE ONLY public.maternal_stats DROP CONSTRAINT maternal_stats_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00238\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024622\00\00\00\00parents parents_nic_key\00
\00\00\00CONSTRAINT\00\00\00\00\00Q\00\00\00ALTER TABLE ONLY public.parents
    ADD CONSTRAINT parents_nic_key UNIQUE (nic);
\00A\00\00\00ALTER TABLE ONLY public.parents DROP CONSTRAINT parents_nic_key;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00216\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024620\00\00\00\00parents parents_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00R\00\00\00ALTER TABLE ONLY public.parents
    ADD CONSTRAINT parents_pkey PRIMARY KEY (id);
\00>\00\00\00ALTER TABLE ONLY public.parents DROP CONSTRAINT parents_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00216\00\00\00\00\00\00\00\00\00\00\00\00*
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024887\00\00\00\00patients patients_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00T\00\00\00ALTER TABLE ONLY public.patients
    ADD CONSTRAINT patients_pkey PRIMARY KEY (id);
\00@\00\00\00ALTER TABLE ONLY public.patients DROP CONSTRAINT patients_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00230\00\00\00\00\00\00\00\00\00\00\00\00"
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024707\00\00\00\00permissions permissions_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00Z\00\00\00ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);
\00F\00\00\00ALTER TABLE ONLY public.permissions DROP CONSTRAINT permissions_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00224\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024658\002\00\00\00public_health_midwives public_health_midwives_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00p\00\00\00ALTER TABLE ONLY public.public_health_midwives
    ADD CONSTRAINT public_health_midwives_pkey PRIMARY KEY (id);
\00\\00\00\00ALTER TABLE ONLY public.public_health_midwives DROP CONSTRAINT public_health_midwives_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00219\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024638\00\00\00\00staffs staffs_license_no_key\00
\00\00\00CONSTRAINT\00\00\00\00\00]\00\00\00ALTER TABLE ONLY public.staffs
    ADD CONSTRAINT staffs_license_no_key UNIQUE (license_no);
\00F\00\00\00ALTER TABLE ONLY public.staffs DROP CONSTRAINT staffs_license_no_key;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00217\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024636\00\00\00\00staffs staffs_nic_key\00
\00\00\00CONSTRAINT\00\00\00\00\00O\00\00\00ALTER TABLE ONLY public.staffs
    ADD CONSTRAINT staffs_nic_key UNIQUE (nic);
\00?\00\00\00ALTER TABLE ONLY public.staffs DROP CONSTRAINT staffs_nic_key;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00217\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024634\00\00\00\00staffs staffs_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00P\00\00\00ALTER TABLE ONLY public.staffs
    ADD CONSTRAINT staffs_pkey PRIMARY KEY (id);
\00<\00\00\00ALTER TABLE ONLY public.staffs DROP CONSTRAINT staffs_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00217\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024613\00\00\00\00users users_email_key\00
\00\00\00CONSTRAINT\00\00\00\00\00Q\00\00\00ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
\00?\00\00\00ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00215\00\00\00\00\00\00\00\00\00\00\00\00
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024611\00\00\00\00users users_pkey\00
\00\00\00CONSTRAINT\00\00\00\00\00N\00\00\00ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
\00:\00\00\00ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00215\00\00\00\00\00\00\00\00\00\00\00\00@
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024731\001\00\00\00admin_permissions admin_permissions_admin_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\92\00\00\00ALTER TABLE ONLY public.admin_permissions
    ADD CONSTRAINT admin_permissions_admin_id_fkey FOREIGN KEY (admin_id) REFERENCES public.admins(id);
\00[\00\00\00ALTER TABLE ONLY public.admin_permissions DROP CONSTRAINT admin_permissions_admin_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\003360\00\00\00\00226\00\00\00\00222\00\00\00\00\00\00\00\00\00\00\00\00A
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024741\003\00\00\00admin_permissions admin_permissions_granted_by_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\96\00\00\00ALTER TABLE ONLY public.admin_permissions
    ADD CONSTRAINT admin_permissions_granted_by_fkey FOREIGN KEY (granted_by) REFERENCES public.admins(id);
\00]\00\00\00ALTER TABLE ONLY public.admin_permissions DROP CONSTRAINT admin_permissions_granted_by_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00226\00\00\00\00222\00\00\00\003360\00\00\00\00\00\00\00\00\00\00\00\00B
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024736\006\00\00\00admin_permissions admin_permissions_permission_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\A1\00\00\00ALTER TABLE ONLY public.admin_permissions
    ADD CONSTRAINT admin_permissions_permission_id_fkey FOREIGN KEY (permission_id) REFERENCES public.permissions(id);
\00`\00\00\00ALTER TABLE ONLY public.admin_permissions DROP CONSTRAINT admin_permissions_permission_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00224\00\00\00\00226\00\00\00\003362\00\00\00\00\00\00\00\00\00\00\00\00>
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024714\00@\00\00\00admin_type_permissions admin_type_permissions_admin_type_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\AB\00\00\00ALTER TABLE ONLY public.admin_type_permissions
    ADD CONSTRAINT admin_type_permissions_admin_type_id_fkey FOREIGN KEY (admin_type_id) REFERENCES public.admin_types(id);
\00j\00\00\00ALTER TABLE ONLY public.admin_type_permissions DROP CONSTRAINT admin_type_permissions_admin_type_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00221\00\00\00\003358\00\00\00\00225\00\00\00\00\00\00\00\00\00\00\00\00?
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024719\00@\00\00\00admin_type_permissions admin_type_permissions_permission_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\AB\00\00\00ALTER TABLE ONLY public.admin_type_permissions
    ADD CONSTRAINT admin_type_permissions_permission_id_fkey FOREIGN KEY (permission_id) REFERENCES public.permissions(id);
\00j\00\00\00ALTER TABLE ONLY public.admin_type_permissions DROP CONSTRAINT admin_type_permissions_permission_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00225\00\00\00\00224\00\00\00\003362\00\00\00\00\00\00\00\00\00\00\00\00<
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024694\00 \00\00\00admins admins_admin_type_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\8B\00\00\00ALTER TABLE ONLY public.admins
    ADD CONSTRAINT admins_admin_type_id_fkey FOREIGN KEY (admin_type_id) REFERENCES public.admin_types(id);
\00J\00\00\00ALTER TABLE ONLY public.admins DROP CONSTRAINT admins_admin_type_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00222\00\00\00\003358\00\00\00\00221\00\00\00\00\00\00\00\00\00\00\00\00=
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024689\00\00\00\00admins admins_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\81\00\00\00ALTER TABLE ONLY public.admins
    ADD CONSTRAINT admins_id_fkey FOREIGN KEY (id) REFERENCES public.users(id) ON DELETE CASCADE;
\00?\00\00\00ALTER TABLE ONLY public.admins DROP CONSTRAINT admins_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00222\00\00\00\00215\00\00\00\003342\00\00\00\00\00\00\00\00\00\00\00\00I
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\00\00\00\0008\00)\00\00\00appointments appointments_patient_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\A1\00\00\00ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_patient_id_fkey FOREIGN KEY (patient_id) REFERENCES public.patients(id) ON DELETE SET NULL;
\00S\00\00\00ALTER TABLE ONLY public.appointments DROP CONSTRAINT appointments_patient_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\003370\00\00\00\00234\00\00\00\00230\00\00\00\00\00\00\00\00\00\00\00\00L
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0025030\00%\00\00\00child_stats child_stats_child_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\9A\00\00\00ALTER TABLE ONLY public.child_stats
    ADD CONSTRAINT child_stats_child_id_fkey FOREIGN KEY (child_id) REFERENCES public.children(id) ON DELETE CASCADE;
\00O\00\00\00ALTER TABLE ONLY public.child_stats DROP CONSTRAINT child_stats_child_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00232\00\00\00\003374\00\00\00\00236\00\00\00\00\00\00\00\00\00\00\00\00F
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024920\00\00\00\00children children_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\88\00\00\00ALTER TABLE ONLY public.children
    ADD CONSTRAINT children_id_fkey FOREIGN KEY (id) REFERENCES public.patients(id) ON DELETE CASCADE;
\00C\00\00\00ALTER TABLE ONLY public.children DROP CONSTRAINT children_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00230\00\00\00\003370\00\00\00\00232\00\00\00\00\00\00\00\00\00\00\00\00G
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024925\00 \00\00\00children children_parent_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\95\00\00\00ALTER TABLE ONLY public.children
    ADD CONSTRAINT children_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES public.parents(id) ON DELETE CASCADE;
\00J\00\00\00ALTER TABLE ONLY public.children DROP CONSTRAINT children_parent_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00216\00\00\00\003346\00\00\00\00232\00\00\00\00\00\00\00\00\00\00\00\00H
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024930\00\00\00\00children children_phm_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\9F\00\00\00ALTER TABLE ONLY public.children
    ADD CONSTRAINT children_phm_id_fkey FOREIGN KEY (phm_id) REFERENCES public.public_health_midwives(id) ON DELETE SET NULL;
\00G\00\00\00ALTER TABLE ONLY public.children DROP CONSTRAINT children_phm_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00219\00\00\00\003356\00\00\00\00232\00\00\00\00\00\00\00\00\00\00\00\008
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024649\00\00\00\00doctors doctors_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\83\00\00\00ALTER TABLE ONLY public.doctors
    ADD CONSTRAINT doctors_id_fkey FOREIGN KEY (id) REFERENCES public.users(id) ON DELETE CASCADE;
\00A\00\00\00ALTER TABLE ONLY public.doctors DROP CONSTRAINT doctors_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\003342\00\00\00\00215\00\00\00\00218\00\00\00\00\00\00\00\00\00\00\00\00J
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0025330\00\00\00\00appointments fk_name\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\83\00\00\00ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT fk_name FOREIGN KEY (phm_id) REFERENCES public.public_health_midwives(id);
\00>\00\00\00ALTER TABLE ONLY public.appointments DROP CONSTRAINT fk_name;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\003356\00\00\00\00234\00\00\00\00219\00\00\00\00\00\00\00\00\00\00\00\00C
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0025340\00\00\00\00maternal fk_name\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\00\00\00ALTER TABLE ONLY public.maternal
    ADD CONSTRAINT fk_name FOREIGN KEY (phm_id) REFERENCES public.public_health_midwives(id);
\00:\00\00\00ALTER TABLE ONLY public.maternal DROP CONSTRAINT fk_name;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00231\00\00\00\00219\00\00\00\003356\00\00\00\00\00\00\00\00\00\00\00\00K
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0025335\00\00\00\00appointments fk_name2\00
\00\00\00FK CONSTRAINT\00\00\00\00\00x\00\00\00ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT fk_name2 FOREIGN KEY (doctor_id) REFERENCES public.doctors(id);
\00?\00\00\00ALTER TABLE ONLY public.appointments DROP CONSTRAINT fk_name2;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\003354\00\00\00\00234\00\00\00\00218\00\00\00\00\00\00\00\00\00\00\00\00D
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024893\00\00\00\00maternal maternal_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\88\00\00\00ALTER TABLE ONLY public.maternal
    ADD CONSTRAINT maternal_id_fkey FOREIGN KEY (id) REFERENCES public.patients(id) ON DELETE CASCADE;
\00C\00\00\00ALTER TABLE ONLY public.maternal DROP CONSTRAINT maternal_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\003370\00\00\00\00231\00\00\00\00230\00\00\00\00\00\00\00\00\00\00\00\00E
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024898\00 \00\00\00maternal maternal_parent_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\96\00\00\00ALTER TABLE ONLY public.maternal
    ADD CONSTRAINT maternal_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES public.parents(id) ON DELETE SET NULL;
\00J\00\00\00ALTER TABLE ONLY public.maternal DROP CONSTRAINT maternal_parent_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\003346\00\00\00\00231\00\00\00\00216\00\00\00\00\00\00\00\00\00\00\00\00M
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0025319\00.\00\00\00maternal_stats maternal_stats_maternal_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\A6\00\00\00ALTER TABLE ONLY public.maternal_stats
    ADD CONSTRAINT maternal_stats_maternal_id_fkey FOREIGN KEY (maternal_id) REFERENCES public.maternal(id) ON DELETE CASCADE;
\00X\00\00\00ALTER TABLE ONLY public.maternal_stats DROP CONSTRAINT maternal_stats_maternal_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00231\00\00\00\003372\00\00\00\00238\00\00\00\00\00\00\00\00\00\00\00\005
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024755\00\00\00\00parents parents_area_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\8D\00\00\00ALTER TABLE ONLY public.parents
    ADD CONSTRAINT parents_area_id_fkey FOREIGN KEY (area_id) REFERENCES public.areas(id) ON DELETE CASCADE;
\00F\00\00\00ALTER TABLE ONLY public.parents DROP CONSTRAINT parents_area_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00228\00\00\00\003368\00\00\00\00216\00\00\00\00\00\00\00\00\00\00\00\006
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024623\00\00\00\00parents parents_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\83\00\00\00ALTER TABLE ONLY public.parents
    ADD CONSTRAINT parents_id_fkey FOREIGN KEY (id) REFERENCES public.users(id) ON DELETE CASCADE;
\00A\00\00\00ALTER TABLE ONLY public.parents DROP CONSTRAINT parents_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\003342\00\00\00\00215\00\00\00\00216\00\00\00\00\00\00\00\00\00\00\00\009
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024760\00:\00\00\00public_health_midwives public_health_midwives_area_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\AB\00\00\00ALTER TABLE ONLY public.public_health_midwives
    ADD CONSTRAINT public_health_midwives_area_id_fkey FOREIGN KEY (area_id) REFERENCES public.areas(id) ON DELETE CASCADE;
\00d\00\00\00ALTER TABLE ONLY public.public_health_midwives DROP CONSTRAINT public_health_midwives_area_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00228\00\00\00\00219\00\00\00\003368\00\00\00\00\00\00\00\00\00\00\00\00:
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024659\005\00\00\00public_health_midwives public_health_midwives_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\A1\00\00\00ALTER TABLE ONLY public.public_health_midwives
    ADD CONSTRAINT public_health_midwives_id_fkey FOREIGN KEY (id) REFERENCES public.users(id) ON DELETE CASCADE;
\00_\00\00\00ALTER TABLE ONLY public.public_health_midwives DROP CONSTRAINT public_health_midwives_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00215\00\00\00\003342\00\00\00\00219\00\00\00\00\00\00\00\00\00\00\00\00;
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024664\00@\00\00\00public_health_midwives public_health_midwives_supervisor_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\B7\00\00\00ALTER TABLE ONLY public.public_health_midwives
    ADD CONSTRAINT public_health_midwives_supervisor_id_fkey FOREIGN KEY (supervisor_id) REFERENCES public.users(id) ON DELETE CASCADE;
\00j\00\00\00ALTER TABLE ONLY public.public_health_midwives DROP CONSTRAINT public_health_midwives_supervisor_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00219\00\00\00\00215\00\00\00\003342\00\00\00\00\00\00\00\00\00\00\00\007
\00\00\00\00\00\00\00\00\00\00\002606\00\00\00\0024639\00\00\00\00staffs staffs_id_fkey\00
\00\00\00FK CONSTRAINT\00\00\00\00\00\81\00\00\00ALTER TABLE ONLY public.staffs
    ADD CONSTRAINT staffs_id_fkey FOREIGN KEY (id) REFERENCES public.users(id) ON DELETE CASCADE;
\00?\00\00\00ALTER TABLE ONLY public.staffs DROP CONSTRAINT staffs_id_fkey;
\00\00\00\00\00\00\00public\00\00\00\00\00\00\00\00\00\00postgres\00\00\00\00false\00\00\00\00217\00\00\00\00215\00\00\00\003342\00\00\00\00\00\00\00\00\00\00\00\00\E8
\00\00\00
\00\00\00x\9C\E3\E2\00\00 \00\00\00\00\00\00\00\E7
\00\00\00
\00\00\00x\9C\E3\E2\00\00 \00\00\00\00\00\00\00\E3
\00\00\00R\00\00\00x\9C\F3\F4v
Q\F0\F4\F1W((M\CA\C9L\D6KL\C9\CD̋/\A9,H-Vs\F4	u
V\D00\D4QP/.-H-R״\E6\F2$N\8FPOJbI"	Z\8C\81ZJ\8B!\B6pq\00\D2/\BE\00\00\00\00\00\00\E4
\00\00\003\00\00\00x\9C\F3\F4v
Q\F0\F4\F1W((M\CA\C9L\D6KL\C9\CD\CC+Vs\F4	u
V\D00\D1Q0Դ\E6\E2\E2\00$\DA\E4\00\00\00\00\00\00\F0
\00\00\00\00\00x\9C\95\91_K\C30\C5\DF\F7)\EE\DB֒?\9D\AE\F5i\8C\82\85Ra\DD|\91\D8ݭŬ\8Dm\CA\F4ۛtB\9DC\EA \DC\\CE=\B9\F0ˉ\924\\AE JV\A0\DAWYd\AEP\AA*J\BD\C7R7\F08\8F\D7a
W|lc%j#\8FM\C7\9B:\94:\D4Jb\8F\95\93J\C3\\CA*7V\C8D\99\A1|\A9\F1\BD\C5\E6[K\8B\BD\92h\BB\A7g[w\B6l\B7\FDZҭ\F5>8uog\9C\F8|pJ'\90\AC\E3\F8\FAn\FD\8Av\8FΠ\88\C38P\DACݣ\90:_,\8C\EB\BF\C0\96U\AB\8BҸr\CC\DEZ\D5\E6yn\AF\C3\E17\85A\F0껞wæ\B3\C1\E9\E5\8C\EC\\A3\C5\E9s
\C4@\AC\B1\C9rܴO1\C3O\84\F0C\EC\8BR\E8\A2*bvq\EE΢\BC\93\C6\D1\E8\A8\F4\AF\FC\00\00\00\00\00\00\EA
\00\00\00\A0\00\00\00x\9C\8D\D0\CB
\C20\85\E1}\9F"\BB*\88X\EF\E2\CAEѠV\B1\D5\FD\A9	Z\DAP
ŷw\E28\FB\FF#\99\A3\B3<\BDJg\C5I9_Ru\A2\B5x\A9\DB\E6pMs\D5K*>\A3\85\F1\E2\FE:\D2\FF\C0\98\C1:a?\E1~_}\E4LlA(a\F0\B4\B5\CD\C2M\CBH\94\CF9\D7\E4\9D\91L,\82(==#\CB\DFN\E2\AD8\DFyBm \DC)\85\9B\AD1p՛(\8A\BECz\94l\00\00\00\00\00\00\F2
\00\00\00
\00\00\00x\9C\E3\E2\00\00 \00\00\00\00\00\00\00\EE
\00\00\00\AF\00\00\00x\9C\F3\F4v
Q\F0\F4\F1W((M\CA\C9L\D6K\CE\C8\CCI)J\CDSs\F4	u
V\D00\D5Q\F0\F5\F1\D1Q0\D4QP\F7J\CCKU\F0J\CCU\B2\8D\8CLu

t
M@\BC\DCĜTu\98R\F5\DC\FC\A2Ғ\D2\F2D\84HAj^Jf^\BA\BA\A65\97'\B6\E9(C\ACt\CCI\ADP\F0\00\9A^\B7\D4\00h\AF\88\97\96
\B5V==??D'\E5\A5\E6\E4\90o\AF9\B2oSR\AA\A0v\9A-\D450\C2\F4(>\FB\B8\B8\00+aM\00\00\00\00\00\00\E0
\00\00\001\00\00\00x\9C\F3\F4v
Q\F0\F4\F1W((M\CA\C9L\D6K\C9O.\C9/*Vs\F4	u
V\D00Ҵ\E6\E2\E2\00\95\E7\00\00\00\00\00\00\ED
\00\00\00\A7\00\00\00x\9C-\8E\B1
\C20\84\F7>\C5O\96*4\D2D\A2\93C\87\82T\B0\D55\C4\E6GiR\92T\F1\DDm\D1\E9\B8\E3\E3)\CF-Tu{\82a\BCݭz\D1[i\E0z8^\CA,\83u)\CF\F9\862Ns\96N\EEM\8CQ\F4hC\F4\A34b@\AF\9D"; ?\90QV\90>\EC"*\A1\D0\E8\FA\97P\D3\C0\9F+h\BE\A5\9C\93\CF\)mD+\A34\B3	\D89\ABD\F4\BA\C70\9A\B3\BBsjR\B6\DC'I\F2/\B56\FE\00\00\00\00\00\00\F4
\00\00\00\00\00x\9C\95\91AK\C40\85\EF\FD\A5\97(\B4!3M\93\B6\9E<,R\90
\EE\AE\91\A5\BB[\D7\C2n#M<\88\EC7mJ\B1\B0\82\9E\F3&3|\F3R\94\CB\C5\E3\CA/\CAՃ\FF\FE\B1=6;z\AALݵ\D5q\A3Me\B4\FFt{\BF^,\FD+}}\82y\C4\E2b+]\EFT\BBߘ\AE9\D5ڎYO$\941\FBvT\8Cif\85\00\A4\B6\99\8D&\84<\BF\B72\89\80Ev*g2G\A0\85Le\DF=(\B5'\D77^\F1gR1\91kA\FEB*\A4N\C7F\00\85mK\9D+Fԯ\A0U\A6\F2\E0\CE\E799\F6\E41\E4\9CQ\B3!\9Cm\F5Op9G\D6oym:m.r\A71\8E\DCC\C2\C0\C4<≻T\E6\ADi\C19\9C,\A3~V\BA\FA\BC|\E6\9CS.\E3!\98\E9C<\EF\D70\92\00\00\00\00\00\00\DE
\00\00\00o\00\00\00x\9C\F3\F4v
Q\F0\F4\F1W((M\CA\C9L\D6+H,J\CD+)Vs\F4	u
V\D00\D6QP\CF\CD/\C9H-R\B2\8C\8C
M
,,\CCA|\AFĴ\B4\BCD \CBTG\C1/\D4\C7GG\C1\D8PӚ˓\B0\B9@
\EAi\89\A8\E6\E31DM\E6\E2\00\CBl/\FA\00\00\00\00\00\00\EC
\00\00\00\A9\00\00\00x\9C\BD\CE\CF
\82@\C7\F1\BBO17j\99\99\FD\C7n\A7\840H\EBn&$\98H\D9\FB\B7`\AC'o_\98\CF//\CA\ECRA^Tg\BF\F7\BEk\C4XO];L\B8O׬\84
\ED }\D5S\FB\EA>
7#\EB=\E1\9E zE\95@e\8C\B5\91\BA=$\F9\8F×\E6\D9\F5\8F50\B9&\A6\E60& \E35zb\A12R\A4.\C4\F4?\CC\FA0_[\A1\AD\91\91\BA\B3\B3:\E6\93\8E\8Ct\A9K\92JB\9E\B5\00\00\00\00\00\00\E6
\00\00\00
\00\00\00x\9C\E3\E2\00\00 \00\00\00\00\00\00\00\E1
\00\00\00D\00\00\00x\9C\F3\F4v
Q\F0\F4\F1W((M\CA\C9LփP\F1\A9\899%\F1\B9\99)\E5\99e\A9\C5
a\8E>\A1\AE\C1
\86:
~\A1>>:
\86\9A\D6\\\\00\D5R\00\00\00\00\00\00\DF
\00\00\00o\00\00\00x\9C\F3\F4v
Q\F0\F4\F1W((M\CA\C9L\D6+.ILK+Vs\F4	u
V\D00\D4QP7\B4\B4054024261S\F2<|u
LM\8C\D55\AD\B9<	a6\C2\D2\C0\DC\C0\D0\D8\C4\D4\CCdD\90\AB\BB\AEK\90\AE\91\81\91\A9\AE\D0`\90I\\\00\CCv#\A8\00\00\00\00\00\00\DD
\00\00\00^\00\00x\9C\AD\92\CDj\C2@F\F7>E\82-\AD\D3;\93\89\C6t\D3TQj\A2Fc]N\E2ؤ&\9Bi|\FA&\A5P
B].\CCǅ9>\EE\C4r\8C\E5J\9AX+[:^\FA\A8\C8x\9AI\AE\FE\B26\E9\86\DCK\87\A5,\E8T!\AB\C3\D3[\CC\C2\F9I\\AFڤlch#d\CDRՒd\E7͍ؒ\F9R\B8\A8\8E
\9B\FD&\9F\BE\92\84\9E\E7\CEi\BF:ysS
\8B\FA\FF.\F1\F3$\AD\A2ta\D0\C5X\C2=\8D\80F1\C2D\00\BD\E8\DC>\B6&\FF\B9\E2\8Ab\85i̢\9A'\BES\B3\B6\C7 nF\95V\94aM\8B\AB7\E6\8B\98\A0\00\CD
\B3]\8A?ʲ\84\ADy\80\A0\8F\FB\AAz\AD\B2\QF<\AC\C68σ<	\80\DCp\C5,\E5"\BFв\ACQ\8A\FA=\D2|\AD\B2Ro!D\F9\AB|Q6qa[\F8#24\D7Iid\BA{{<\C3ϟ\89\AE\F2ia\DA:_$\BD\8C\9A\DBY\E9\AC\DFG߾ \8B\A1K\88\AA\A6\A8\AD\FAc\F2#\DBj}\E2\97\F7"\00\00\00\00\00
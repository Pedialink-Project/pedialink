INSERT INTO
    parents (
        id,
        type,
        nic,
        address,
        phone_number,
        area_id
    )
VALUES (
        (
            SELECT id
            FROM users
            WHERE
                email = 'keeththi2003@gmail.com'
            LIMIT 1
        ),
        'father',
        '200315000887',
        'Jaffna',
        5
    );

INSERT INTO
    patients (id, type)
VALUES (1, 'maternal'),
    (2, 'child'),
    (3, 'child');

INSERT INTO
    maternal (id, parent_id)
VALUES (
        1,
        (
            SELECT id
            FROM users
            WHERE
                email = 'keeththi2003@gmail.com'
            LIMIT 1
        )
    );

INSERT INTO
    children (
        id,
        parent_id,
        phm_id,
        name,
        date_of_birth,
        gender,
        health_status
    )
VALUES (
        2,
        (
            SELECT id
            FROM users
            WHERE
                email = 'keeththi2003@gmail.com'
            LIMIT 1
        ),
        (
            SELECT id
            FROM users
            WHERE
                email = 'nirmal@gmail.com'
            LIMIT 1
        ),
        'Alex Hales',
        '2025-05-12',
        'male',
        'good'
    );

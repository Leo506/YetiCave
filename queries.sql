DELETE FROM Category;
DELETE FROM User;
DELETE FROM Lot;
DELETE FROM Bet;


ALTER TABLE Category AUTO_INCREMENT=1;

INSERT INTO Category(name, code)
VALUES
        ("Доски и лыжи", "boards"),
        ("Крепления", "attachment"),
        ("Ботинки", "boots"),
        ("Одежда", "clothing"),
        ("Инструменты", "tools"),
        ("Разное", "other");

ALTER TABLE User AUTO_INCREMENT=1;

INSERT INTO User(email, name, password, contacts)
VALUES
        ("someemail@gmail.com", "Some name", MD5("hello"), "8 999 999 99 99"),
        ("test@mail.com", "Test", MD5("hello"), "8 999 999 99 99"),
        ("who@gmail.com", "Who am I?", MD5("hello"), "8 999 999 99 99");

ALTER TABLE Lot AUTO_INCREMENT=1;

INSERT INTO Lot(name, description, image, start_price, end_date, step, authorId, categoryId)
VALUES
        ("2014 Rossignol District Snowboard", "some description", "img/lot-1.jpg", 1000, "2023-09-11", 100, 1, 1),
        ("DC Ply Mens 2016/2017 Snowboard", "some description", "img/lot-2.jpg", 1000, "2023-09-13", 100, 2, 1),
        ("Крепления Union Contact Pro 2015 года размер L/XL", "some description", "img/lot-3.jpg", 1000, "2024-09-12", 100, 3, 2),
        ("Ботинки для сноуборда DC Mutiny Charocal", "some description", "img/lot-4.jpg", 1000, "2023-09-20", 100, 1, 3),
        ("Куртка для сноуборда DC Mutiny Charocal", "some description", "img/lot-5.jpg", 1000, "2023-10-12", 100, 2, 4),
        ("Маска Oakley Canopy", "some description", "img/lot-6.jpg", 1000, "2023-12-12", 100, 3, 6);

ALTER TABLE Bet AUTO_INCREMENT=1;

INSERT INTO Bet(summ, userId, lotId)
VALUES
        (1000, 2, 1),
        (1500, 3, 1);

/*получить список всех категорий*/
SELECT * FROM Category;

/*получить cписок лотов, которые еще не истекли отсортированных по дате публикации, от новых к старым.
Каждый лот должен включать название, стартовую цену, ссылку на изображение, название категории и дату окончания торгов*/
SELECT l.name,  l.start_price, l.image, c.name "category", l.end_date FROM Lot l
    INNER JOIN Category c
       ON l.categoryId = c.id
    WHERE end_date >= CURRENT_DATE
    ORDER BY creating_date DESC;

/*показать информацию о лоте по его ID. Вместо id категории должно выводиться  название категории,
к которой принадлежит лот из таблицы категорий;*/
SELECT l.name, l.creating_date, l.description, l.step, l.authorId, l.winnerId,  l.start_price, l.image, c.name "category", l.end_date FROM Lot l
    INNER JOIN Category c
        ON l.categoryId = c.id
    WHERE l.id = @id;

/*обновить название лота по его идентификатору*/
UPDATE Lot
SET name = @new_name
WHERE id = @id;

/*получить список ставок для лота по его идентификатору с сортировкой по дате. Список должен содержать дату и время
размещения ставки, цену, по которой пользователь готов приобрести лот, название лота и имя пользователя, сделавшего ставку*/
SELECT b.date, b.summ, l.name, u.name FROM Bet b
    INNER JOIN Lot l
        ON b.lotId = l.id
    INNER JOIN User u
        ON b.userId = u.Id
    WHERE l.id = @lot_id
    ORDER BY b.date;
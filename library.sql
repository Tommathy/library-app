create table if not exists library_app.books
(
    id               bigint unsigned auto_increment
        primary key,
    title            tinytext not null,
    author           tinytext not null,
    publication_year year     not null,
    number_of_pages  int      not null
)
    comment 'All books in the library';


create table if not exists library_app.loans
(
    id              bigint unsigned auto_increment comment 'Unique loan id'
        primary key,
    book_id         bigint unsigned not null comment 'The book identifier',
    borrower_name   tinytext        not null comment 'The name of who has borrowed the book',
    loan_start_date date            not null comment 'The start date of the loan',
    loan_end_date   date            null comment 'the end date of the loan',
    constraint loans_book_id_foreign
        foreign key (book_id) references library_app.books (id)
)
    comment 'Records of books that have been loaned out to specific people';


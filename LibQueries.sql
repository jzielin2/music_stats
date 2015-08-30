use iTunes_analyzer;

/***************************************************************
 ***  Songs in library
 ***************************************************************/
 select count(*) AS 'Total songs in library'
 from songs
 where (genre <> 'Podcast' or genre is null);
 
 /***************************************************************
 /***  # songs you've never listened to
 /***************************************************************/
 select count(*)
 from songs
 where playDateUTC is null
 and (genre <> 'Podcast' or genre is null);
 
 /***************************************************************
 ***  # songs / artist
 ***************************************************************/
 select artist, count(*) AS 'Most popular artists in library'
 from songs
 where (genre <> 'Podcast' or genre is null)
 and artist <> ''
 group by artist
 order by 2 desc
 limit 10;
 
 /***************************************************************
 ***  top decades in library
 ***************************************************************/
select 
	CASE floor(right(Year, 2)/10)*10
		when 90 THEN '1990\'s'
		when 80 THEN '1980\'s'
		when 70 THEN '1970\'s'
		when 60 THEN '1960\'s'
		when 0  THEN '2000\'s'
		when 10 THEN '2010\'s'
	END AS `Decade`,
    count(*) AS 'Most popular decade in library'
from songs
where CASE floor(right(Year, 2)/10)*10
		when 90 THEN '1990\'s'
		when 80 THEN '1980\'s'
		when 70 THEN '1970\'s'
		when 60 THEN '1960\'s'
		when 0  THEN '2000\'s'
		when 10 THEN '2010\'s'
	END is not null
group by 1
order by 1 desc;
 
/***************************************************************
 ***  top genre in library
 ***************************************************************/
 select genre, count(*) AS 'Most popular genre in library'
 from songs
 where genre <> 'Podcast'
 group by genre
 order by 2 desc
 limit 10;

/***************************************************************
 ***  last 5 played tracks
 ***************************************************************/
select name, artist
from songs
where (genre <> 'Podcast' or genre is null)
order by playDateUTC desc
limit 5;


/***************************************************************
 ***  Total songs played (all songs)
 ***************************************************************/
select sum(playCount) AS 'Total song plays'
from songs;

/***************************************************************
 ***  Total time played (all songs)
 ***************************************************************/
select ROUND(SUM(PlayCount*TotalTime/1000/60/60), 2) AS 'Total time played (hours)'
from songs;

/***************************************************************
 ***  Top Artists by play count
 ***************************************************************/
select Artist, sum(PlayCount) AS 'Top play by artist'
from songs
group by Artist
order by 2 desc
limit 10;

/***************************************************************
 ***  Top Artists by time listened
 ***************************************************************/
select Artist, ROUND(SUM(PlayCount*TotalTime/1000/60/60), 2) AS 'Top time per artist (hours)'
from songs
group by Artist
order by 2 desc
limit 10;

/***************************************************************
 ***  Top Songs by play count
 ***************************************************************/
select `Name`, sum(PlayCount) AS 'Top play by song'
from songs
group by `Name`
order by 2 desc
limit 10; 

/***************************************************************
 ***  Top Songs by time listened
 ***************************************************************/
select `Name`, ROUND(SUM(PlayCount*TotalTime/1000/60/60), 2) AS 'Top time per song (hours)'
from songs
group by `Name`
order by 2 desc
limit 10;

select *
from songs
where Name = 'The Murder Room: An Adam Dalgliesh Mystery (Unabridged), Part 1';

/***************************************************************
 ***  Top Genre by play count
 ***************************************************************/
select Genre, sum(PlayCount)
from songs
where Genre is not NULL
group by Genre
order by 2 desc;

/***************************************************************
 ***  Top Decade by play count
 ***************************************************************/
select 
	CASE floor(right(Year, 2)/10)*10
		when 90 THEN '1990\'s'
		when 80 THEN '1980\'s'
		when 70 THEN '1970\'s'
		when 60 THEN '1960\'s'
		when 0  THEN '2000\'s'
		when 10 THEN '2010\'s'
	END AS `Decade`,
    sum(PlayCount) AS 'Total songs played'
from songs
where CASE floor(right(Year, 2)/10)*10
		when 90 THEN '1990\'s'
		when 80 THEN '1980\'s'
		when 70 THEN '1970\'s'
		when 60 THEN '1960\'s'
		when 0  THEN '2000\'s'
		when 10 THEN '2010\'s'
	END is not null
group by 1
order by 1 desc;

select *
from songs;

/*truncate table songs;*/

Запрос по сути странный, он на выходе выдаст кашу из дублирущихся записей для всех вариатов many-to-many соответствий. Для начала его можно чуть развернуть, чтобы не выдавало лишних полей:
```
SELECT 
	data.id as data_id,
	data.date,
	data.value,
	info.id as info_id,
	info.name,
	info.desc
FROM data,link,info 
WHERE link.info_id = info.id AND link.data_id = data.id
```

Во-вторых, в таблице **link** не хватает индексов, поэтому ее стоило бы криейтить так:
```
CREATE TABLE `link` (
  `data_id` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  KEY `info_ind` (`info_id`),
  KEY `data_ind` (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

По сути такой набор таблиц соответствует чему-то вроде тэгов к записям, поэтому для большей наглядности данных (ну, это если нужно просмотреть, и количество привязанных объектов небольшое), я бы запрос сделал таким:
```
select i.*,group_concat(d.value) from info i
left join link l on l.info_id=i.id
left join data d on d.id=l.data_id
group by i.id
```
Не понятно, какая перед первоначальным запросом стоит задача - выбрать кашу? а что с ней потом делать?
**Рассматривать вариант переноса таблиц на InnoDB не буду, так как не ясны задачи :)*
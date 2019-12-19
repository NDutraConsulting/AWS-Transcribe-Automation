CREATE TABLE lecture_actions (
  id INT(10) AUTOINCREMENT,
  sentence VARCHAR(),
  sentence_id INT(19) UNSIGNED,
  lecture_id INT(10) UNSIGNED,
  section_id INT(10) UNSIGNED,
  course_id INT(10) UNSIGNED,
  action enum('next_slide', 'prev_slide', 'next_pane', 'prev_pane', 'start_rec', 'stop_rec', 'toggle_block', 'say'),
  created_at TIMESTAMP,
  updated_at TIMESTAMP )

//Problems that I see include accidentally duplicating entries
because the id-autoincrement as the primary key prevents a more appropriate unique key
The best unique key would probably be created_at and sentence


Another option would be to use joins on a sentence id

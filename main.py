#! /usr/bin/env python3

from docx import Document
import re

file_input = 'history_old.docx'
file_output = 'questions_old.json'
file_data_error = 'data_old.json'

class JSON_Object:
    def set_title(self, title):
        self.title = title

    def set_description(self, description):
        self.description = description

    def set_answer(self, answer):
        self.answer = answer

    def set_answers(self, answers):
        self.answers = answers

    def get_title(self):
        return self.title

    def get_description(self):
        return self.description

    def get_answer(self):
        return self.answer

    def get_answers(self):
        return self.answers


def gen_json(ob):   
    
    title = ob.get_title()
    description = ob.get_description()
    answer = ''
    answers = ''

    for ans in ob.get_answer():
        answer += '\t\t\t"' + ans + '",\n'

    answer = answer[:-2] + '\n'
    
    for ans in ob.get_answers():
        answers += '\t\t\t"' + ans + '",\n'

    answers = answers[:-2] + '\n'


    return '\t{\n'                                      \
    '\t\t"title": "' + title + '",\n'                   \
    '\t\t"description": "' + description + '",\n'       \
    '\t\t"right_answers": [\n' + answer + '\t\t],\n'  \
    '\t\t"answers": [\n' + answers  + '\t\t]\n'         \
    '\t}'


# Открыть файл Word
doc = Document(file_input)

def check_q(q):
    res = re.findall("^\d+\.\s", q)
    return res != []


doc_p = []
for paragraph in doc.paragraphs:
    is_answ = False
    for run in paragraph.runs:
        if run.bold:
            is_answ = True

    if is_answ:
        doc_p.append('+' + paragraph.text)
    else:
        doc_p.append(paragraph.text)

test_count = []


def crear_file(file_path):
    # Открытие файла в режиме чтения и записи
    with open(file_path, 'r+') as file:
        file.truncate(0)  # Обрезка файла до размера 0

def gen_ob(document):
    global test_count
    obects = []
    ip = 0
    
    file_data = '[\n'

    while ip < len(document):
        title = ''
        description = ''
        answer = []
        answers = []

        if check_q(document[ip]):   
            num_q = re.findall("^\d+\.\s", document[ip])[0] 

            if not num_q[:-2] in test_count:
                test_count.append(num_q[:-2])
            else:
                print("Повторяется: ", num_q[:-2])


            title = "Вопрос " + num_q    
            description = document[ip][len(num_q):]
            ip += 1

            file_data += '\t{ "id": ' + num_q[:-2] + ', "value": 0 },\n'

            while ip < len(document) and not check_q(document[ip]):
                if document[ip][0] == '+':
                    answer.append(document[ip][1:])
                    answers.append(document[ip][1:])
                else:
                    answers.append(document[ip])

                ip += 1

            ob = JSON_Object()
            ob.set_title(title)
            ob.set_description(description)
            ob.set_answer(answer)
            ob.set_answers(answers)

            json_ob = gen_json(ob)

            obects.append(json_ob)

    file_data = file_data[:-2] + ('\n]')
    file = open(file_data_error, 'w')    
    file.write(file_data)
    file.close()

    return obects


with open(file_output, 'w') as file:
    doc = '[\n'
    for ob in gen_ob(doc_p):
        doc += ob
        doc += ',\n'

    doc = doc[:-2] + '\n'
    doc += ']'

    file.write(doc)

print("Всего вопростов: ", len(test_count))

exit()
print("Отсутствуют вопросы:")
for i in range(1, 1001):
    if not str(i) in test_count:
        print(i)
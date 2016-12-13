#!/usr/bin/python3

import serial, pymysql, datetime

# serial line connection via ttyACM0 (USB)
ser = serial.Serial('/dev/ttyACM0', 9600, timeout=1)

# SQL connections, only have one uncommented at a time
conn = pymysql.connect(host='ADDRESS', user='USER', passwd='PASSWORD', db='DATABASE')
cur = conn.cursor()


def send_to_mysql(x):
    global cur
    # get the timestamp and split into date and time
    date_time = str(datetime.datetime.now()).split()
    date = date_time[0] + '","'
    timestamp = date_time[1].split('.')[0] + '","'

    # construct the message for sql insertion
    message = 'INSERT INTO TABLE_NAME (Column1,Column2,Column3,Column4) VALUES("'
    message += date
    message += timestamp
    message += x[0] + '","'
    message += x[1]
    message += '")'
    
    # execute and commit
    cur.execute(message)
    conn.commit()


def read_serial():
    global ser
    # Read from serial line and convert to string, split the data and remove extra letters \r\n from the end
    message = str(ser.readline()).split("'")[1][:-4]
    # split the remaining message in order to handle the data easier in the next step
    message = message.split(":")
    
    # if not enough data, stop the function and return an empty string
    if (len(message) < 2):
        return ''
    # a list into which the data is stored
    serial_mes = []
    # temperature data
    serial_mes.append(message[0])
    # light data
    serial_mes.append(message[1])
        
    return serial_mes


while(True):
    # Read serial data and save to variable serial_x
    serial_x = read_serial() 
    # do only if serial_x is NOT empty
    if not serial_x == '':
            print(serial_x)
            # Try to send data to sql database, print error message if sending fails
            # and star from beginning of the current loop
            try:
                send_to_mysql(serial_x)
            except:
                print("Error with sending data!")
                continue

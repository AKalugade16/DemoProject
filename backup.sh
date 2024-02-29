#!/bin/bash

# Configuration
PROJECT_FOLDER="https://github.com/AKalugade16/DemoProject.git"
BACKUP_DIR="rclone-v1.65.2-linux-amd64/DPBackup/"
PROJECT_NAME="/rclone-v1.65.2-linux-amd64/DPBackup/cal1.php"
GOOGLE_DRIVE_FOLDER="DemoProject"
ROTATION_DAYS=7
ROTATION_WEEKS=4
ROTATION_MONTHS=3
CURL_URL="https://webhook.site/cal1.php"
#CURL_ENABLED=true
Webhook_url=$1
git clone "$PROJECT_FOLDER" "$BACKUP_DIR"  
# Function to create backup
TIMESTAMP=$(date +%Y-%m-%d_%H-%M-%S)
cd "$BACKUP_DIR"
BACKUP_NAME="backup_$TIMESTAMP.zip"
zip -r "$BACKUP_NAME" .

# Function to upload to Google Drive
rclone copy "$BACKUP_NAME" "remote:$GOOGLE_DRIVE_FOLDER/"

make_curl_request() {
    local project_name=$1
    local backup_date=$2
    local test_identifier=$3
    local url=$4

    curl -X POST -H "Content-Type: application/json" -d "{\"project\": \"$project_name\", \"date\": \"$backup_date\", \"test\": \"$test_identifier\"}" $CURL_URL
}

rclone lsf "remote:$GOOGLE_DRIVE_FOLDER/" | sort -r | awk -v daily=$ROTATION_DAYS -v weekly=$ROTATION_WEEKS -v monthly=$ROTATION_MONTHS '
        BEGIN {
            daily_count = 0;
            weekly_count = 0;
            monthly_count = 0;
        }
        {
            if ($0 ~ /daily/) {
                if (daily_count >= daily) {
                    system("rclone delete \"remote:$GOOGLE_DRIVE_FOLDER/" $0 "\"");
                }
                daily_count++;
            }
            else if ($0 ~ /weekly/) {
                if (weekly_count >= weekly) {
                    system("rclone delete \"remote:$GOOGLE_DRIVE_FOLDER/" $0 "\"");
                }
                weekly_count++;
            }
            else if ($0 ~ /monthly/) {
                if (monthly_count >= monthly) {
                    system("rclone delete \"remote:$GOOGLE_DRIVE_FOLDER/" $0 "\"");
                }
                monthly_count++;
            }
        }'
  	if [ $? -eq 0 ]; then
       		echo "Backup successful: $TIMESTAMP"
        # Make cURL request on successful backup
     		make_curl_request "$PROJECT_NAME" "$TIMESTAMP" "BackupSuccessful" "$CURL_URL"
    	else
        	echo "Backup failed: $backup_date"
        	make_curl_request "$PROJECT_NAME" "$TIMESTAMP" "BackupFailed" "$CURL_URL"
    	fi 

